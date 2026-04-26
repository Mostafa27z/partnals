<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Expense;
use App\Models\Capital;
use App\Models\Role;

class AccountingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $adminRole;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the admin role needed for the middleware
        $this->adminRole = Role::create(['name' => 'admin']);
    }

    public function test_accounting_dashboard_renders_correctly()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->get(route('accounting.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.accounting.dashboard');
    }

    public function test_admin_can_store_capital()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->post(route('accounting.capital.store'), [
            'amount' => 5000,
            'date' => '2026-04-10',
            'description' => 'شراكة جديدة'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('capitals', [
            'amount' => 5000,
            'description' => 'شراكة جديدة'
        ]);
    }

    public function test_admin_can_store_expense()
    {
        $admin = User::factory()->create(['role_id' => $this->adminRole->id]);

        $response = $this->actingAs($admin)->post(route('accounting.expense.store'), [
            'amount' => 250,
            'category' => 'وجبات',
            'date' => '2026-04-15',
            'description' => 'غداء للموظفين'
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('expenses', [
            'amount' => 250,
            'user_id' => $admin->id,
            'category' => 'وجبات'
        ]);
    }
}
