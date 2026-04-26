<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Target;
use App\Models\Bonus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HRTargetTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $employee;
    protected $adminRole;
    protected $employeeRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'admin']);
        $this->employeeRole = Role::create(['name' => 'tele sales']);

        $this->admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $this->employee = User::factory()->create(['role_id' => $this->employeeRole->id]);
    }

    /** @test */
    public function test_general_target_is_assigned_to_non_admin_users()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('hr.target.store'), [
            'name' => 'Target All',
            'type' => 'general',
            'threshold' => 10,
            'reward' => 100
        ]);

        $target = Target::where('name', 'Target All')->first();
        $this->assertNotNull($target);
        
        // Emp should have it, Admin should not
        $this->assertTrue($this->employee->targets->contains($target->id));
        $this->assertFalse($this->admin->targets->contains($target->id));
    }

    /** @test */
    public function test_bulk_target_assignment()
    {
        $this->actingAs($this->admin);

        $target = Target::create([
            'name' => 'Specific Target',
            'type' => 'specific',
            'threshold' => 5,
            'reward' => 50
        ]);

        $this->post(route('hr.target.assign'), [
            'user_ids' => [$this->employee->id],
            'target_id' => $target->id
        ]);

        $this->assertTrue($this->employee->targets->contains($target->id));
    }

    /** @test */
    public function test_automatic_reward_release_on_threshold_hit()
    {
        // 1. Create Target
        $target = Target::create([
            'name' => 'Sales Target',
            'type' => 'general',
            'threshold' => 1,
            'reward' => 100
        ]);
        $this->employee->targets()->attach($target);

        // 2. Create a "done" request to simulate a sale
        // Need a line first
        $line = \App\Models\Line::create([
            'phone_number' => '0123456789',
            'status' => 'active',
            'provider' => 'Vodafone',
            'serial_number' => 'SN123',
        ]);

        \App\Models\Request::create([
            'line_id' => $line->id,
            'customer_id' => null,
            'request_type' => 'resell',
            'status' => 'done',
            'requested_by' => $this->employee->id,
            'updated_at' => now(),
        ]);
        
        // 3. Trigger check
        $this->employee->checkAndReleaseTargets(now()->month, now()->year);

        // 4. Verify Bonus created
        $this->assertDatabaseHas('bonuses', [
            'user_id' => $this->employee->id,
            'target_id' => $target->id,
            'amount' => 100
        ]);
    }

    /** @test */
    public function test_admin_excluded_from_automatic_rewards()
    {
        $target = Target::create([
            'name' => 'Admin Target',
            'type' => 'general',
            'threshold' => 1,
            'reward' => 100
        ]);
        
        // Admin makes a sale
        $line = \App\Models\Line::create([
            'phone_number' => '01234567899',
            'status' => 'active',
            'provider' => 'Vodafone',
            'serial_number' => 'SN1234',
        ]);

        \App\Models\Request::create([
            'line_id' => $line->id,
            'status' => 'done',
            'request_type' => 'resell',
            'requested_by' => $this->admin->id,
            'updated_at' => now(),
        ]);
        
        $this->admin->checkAndReleaseTargets(now()->month, now()->year);

        $this->assertDatabaseMissing('bonuses', [
            'user_id' => $this->admin->id,
            'target_id' => $target->id
        ]);
    }
}
