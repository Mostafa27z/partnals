<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Target;
use App\Models\Role;
use Carbon\Carbon;

class HRFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role needed for the middleware
        $this->adminRole = Role::create(['name' => 'admin']);
    }

    public function test_hr_dashboard_renders_correctly()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->get(route('hr.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.hr.dashboard');
    }

    public function test_hr_can_store_advance_for_employee()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $employee = User::factory()->create(['base_salary' => 4000]);

        $response = $this->actingAs($admin)->post(route('hr.advance.store'), [
            'user_id' => $employee->id,
            'amount' => 500,
            'date' => Carbon::now()->format('Y-m-d'),
            'notes' => 'سلفة طارئة'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('advances', [
            'user_id' => $employee->id,
            'amount' => 500,
            'status' => 'approved'
        ]);
    }

    public function test_hr_can_store_free_bonus_for_employee()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $employee = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('hr.bonus.store'), [
            'user_id' => $employee->id,
            'amount' => 300,
            'date' => Carbon::now()->format('Y-m-d'),
            'reason' => 'حضور مبكر'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('bonuses', [
            'user_id' => $employee->id,
            'amount' => 300,
            'reason' => 'حضور مبكر'
        ]);
    }

    public function test_hr_can_pay_salary()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $employee = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('hr.salary.pay'), [
            'user_id' => $employee->id,
            'amount' => 4500,
            'month' => 4,
            'year' => 2026
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('salaries', [
            'user_id' => $employee->id,
            'amount' => 4500,
            'month' => 4,
            'year' => 2026,
            'status' => 'paid'
        ]);
    }

    public function test_hr_can_create_new_target()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->post(route('hr.target.store'), [
            'name' => 'Target 50',
            'type' => 'general',
            'threshold' => 50,
            'reward' => 1000
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('targets', [
            'name' => 'Target 50',
            'type' => 'general',
            'threshold' => 50,
            'reward' => 1000
        ]);
    }

    public function test_hr_can_assign_specific_target_to_employee()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);
        $employee = User::factory()->create();
        $target = Target::create([
            'name' => 'Specific Target',
            'type' => 'specific',
            'threshold' => 10,
            'reward' => 200
        ]);

        $response = $this->actingAs($admin)->post(route('hr.target.assign'), [
            'user_ids' => [$employee->id],
            'target_id' => $target->id
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('target_user', [
            'user_id' => $employee->id,
            'target_id' => $target->id
        ]);
    }
}
