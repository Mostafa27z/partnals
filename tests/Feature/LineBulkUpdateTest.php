<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Role;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineBulkUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();

        $this->adminRole = Role::create(['name' => 'admin']);
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);

        $this->distRole = Role::create(['name' => 'موزع']);
        $this->dist1 = User::create(['name' => 'Dist 1', 'email' => 'd1@test.com', 'password' => bcrypt('password'), 'role_id' => $this->distRole->id]);
        $this->dist2 = User::create(['name' => 'Dist 2', 'email' => 'd2@test.com', 'password' => bcrypt('password'), 'role_id' => $this->distRole->id]);

        Provider::create(['name' => 'Vodafone', 'invoice_day' => 1]);
        Provider::create(['name' => 'Orange', 'invoice_day' => 1]);

        // Create some lines
        Line::create(['phone_number' => '01234567891', 'provider' => 'Vodafone', 'distributor_id' => null]);
        Line::create(['phone_number' => '01234567892', 'provider' => 'Vodafone', 'distributor_id' => null]);
        Line::create(['phone_number' => '01234567893', 'provider' => 'Orange', 'distributor_id' => null]);
    }

    public function test_bulk_assign_to_selected_lines()
    {
        $lines = Line::where('provider', 'Vodafone')->get();
        
        $response = $this->actingAs($this->admin)->post(route('lines.bulk-update-distributor'), [
            'bulk_action' => 'assign',
            'bulk_distributor_id' => $this->dist1->id,
            'selected_lines' => $lines->pluck('id')->toArray(),
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, Line::where('distributor_id', $this->dist1->id)->count());
        $this->assertEquals(1, Line::where('distributor_id', null)->count());
    }

    public function test_bulk_assign_to_all_matching_lines()
    {
        // Total lines are 3. 2 are Vodafone.
        $response = $this->actingAs($this->admin)->post(route('lines.bulk-update-distributor'), [
            'bulk_action' => 'assign',
            'bulk_distributor_id' => $this->dist2->id,
            'apply_to_all' => '1',
            'provider' => 'Vodafone', // The filter
        ]);

        $response->assertRedirect();
        $this->assertEquals(2, Line::where('distributor_id', $this->dist2->id)->count());
        $this->assertEquals(1, Line::where('distributor_id', null)->count());
    }

    public function test_bulk_remove_distributor()
    {
        // First assign
        Line::query()->update(['distributor_id' => $this->dist1->id]);
        $this->assertEquals(3, Line::where('distributor_id', $this->dist1->id)->count());

        $response = $this->actingAs($this->admin)->post(route('lines.bulk-update-distributor'), [
            'bulk_action' => 'remove',
            'apply_to_all' => '1',
        ]);

        $response->assertRedirect();
        $this->assertEquals(0, Line::whereNotNull('distributor_id')->count());
    }

    public function test_distributor_isolation_in_bulk_update()
    {
        // Create a line for dist 2
        $line2 = Line::where('phone_number', '01234567893')->first();
        $line2->update(['distributor_id' => $this->dist2->id]);

        // dist 1 tries to bulk update "apply to all" without filters
        // but global scope should restrict him to only HIS lines (which are 0 currently)
        $response = $this->actingAs($this->dist1)->post(route('lines.bulk-update-distributor'), [
            'bulk_action' => 'assign',
            'bulk_distributor_id' => $this->dist1->id,
            'apply_to_all' => '1',
        ]);

        $this->assertEquals(0, Line::withoutGlobalScopes()->where('distributor_id', $this->dist1->id)->count());
        $this->assertEquals(1, Line::withoutGlobalScopes()->where('distributor_id', $this->dist2->id)->count());
    }
}
