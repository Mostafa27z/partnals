<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\User;
use App\Models\Role;
use App\Models\Customer;
use App\Models\Request as RequestModel;
use App\Models\RequestChangeChip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeChipCustomerSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $line;

    protected function setUp(): void
    {
        parent::setUp();
        
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        
        $this->line = Line::factory()->create([
            'phone_number' => '01012345678',
            'distributor_id' => $this->admin->id
        ]);
    }

    /** @test */
    public function it_creates_new_customer_and_associates_line_on_change_chip_completion()
    {
        $this->actingAs($this->admin);

        // 1. Create a change_chip request
        $requestRecord = RequestModel::create([
            'line_id'      => $this->line->id,
            'customer_id'  => $this->line->customer_id,
            'request_type' => 'change_chip',
            'status'       => 'pending',
            'requested_by' => $this->admin->id,
        ]);

        RequestChangeChip::create([
            'request_id'   => $requestRecord->id,
            'change_type'  => 'chip',
            'old_serial'   => '1234567890123456789',
            'new_serial'   => '9876543210987654321',
            'request_date' => now()->toDateString(),
            'full_name'    => 'New Customer Name',
            'national_id'  => '29901011234567', // 14 digits
            'comment'      => 'Test Comment',
        ]);

        // 2. Complete the request by updating its status to "done" via controller
        $response = $this->put("/admin/requests/{$requestRecord->id}", [
            'status' => 'done',
            'old_status' => 'pending'
        ]);

        $response->assertRedirect();
        
        // 3. Verify Customer was created in database
        $this->assertDatabaseHas('customers', [
            'full_name' => 'New Customer Name',
            'national_id' => '29901011234567',
        ]);

        $customer = Customer::where('national_id', '29901011234567')->first();
        $this->assertNotNull($customer);

        // 4. Verify Line was updated with new customer ID and new serial
        $this->line->refresh();
        $this->assertEquals($customer->id, $this->line->customer_id);
        $this->assertEquals('9876543210987654321', $this->line->serial_number);

        // 5. Verify Request was updated with new customer ID
        $requestRecord->refresh();
        $this->assertEquals($customer->id, $requestRecord->customer_id);
        $this->assertEquals('done', $requestRecord->status);
    }

    /** @test */
    public function it_associates_existing_customer_on_change_chip_completion()
    {
        $this->actingAs($this->admin);

        // Create an existing customer
        $existingCustomer = Customer::create([
            'full_name' => 'Existing Customer',
            'national_id' => '29901011234567'
        ]);

        // Create a change_chip request
        $requestRecord = RequestModel::create([
            'line_id'      => $this->line->id,
            'customer_id'  => $this->line->customer_id,
            'request_type' => 'change_chip',
            'status'       => 'pending',
            'requested_by' => $this->admin->id,
        ]);

        RequestChangeChip::create([
            'request_id'   => $requestRecord->id,
            'change_type'  => 'chip',
            'old_serial'   => '1234567890123456789',
            'new_serial'   => '9876543210987654321',
            'request_date' => now()->toDateString(),
            'full_name'    => 'New Name But Same NID', // Different name but same national_id
            'national_id'  => '29901011234567',
            'comment'      => 'Test Comment',
        ]);

        // Complete the request
        $response = $this->put("/admin/requests/{$requestRecord->id}", [
            'status' => 'done',
            'old_status' => 'pending'
        ]);

        $response->assertRedirect();
        
        // Verify no extra customer was created
        $this->assertDatabaseCount('customers', 1);

        // Verify Line was updated with existing customer's ID
        $this->line->refresh();
        $this->assertEquals($existingCustomer->id, $this->line->customer_id);
    }
}
