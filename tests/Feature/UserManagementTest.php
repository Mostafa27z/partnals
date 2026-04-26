<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();

        // Roles
        $this->adminRole = Role::create(['name' => 'admin']);
        $this->distRole = Role::create(['name' => 'موزع']);

        // Permissions
        $this->manageUsersPermission = Permission::create(['name' => 'manage users']);
        $this->adminRole->permissions()->sync([$this->manageUsersPermission->id]);

        // Users
        $this->admin1 = User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);

        $this->admin2 = User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);

        $this->distributor = User::create([
            'name' => 'Distributor',
            'email' => 'dist@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->distRole->id
        ]);

        // Line for distributor
        $this->line = Line::create([
            'phone_number' => '0123456789',
            'distributor_id' => $this->distributor->id,
            'provider' => 'Vodafone',
            'gcode' => 'VOD',
            'line_type' => 'prepaid'
        ]);
    }

    public function test_admin_cannot_delete_self()
    {
        $response = $this->actingAs($this->admin1)->delete(route('users.destroy', $this->admin1));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin1->id, 'deleted_at' => null]);
    }

    public function test_admin_cannot_delete_another_admin()
    {
        $response = $this->actingAs($this->admin1)->delete(route('users.destroy', $this->admin2));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin2->id, 'deleted_at' => null]);
    }

    public function test_deleting_distributor_soft_deletes_their_lines()
    {
        $response = $this->actingAs($this->admin1)->delete(route('users.destroy', $this->distributor));
        
        $response->assertRedirect();
        $this->assertSoftDeleted('users', ['id' => $this->distributor->id]);
        $this->assertSoftDeleted('lines', ['id' => $this->line->id]);
    }

    public function test_restoring_distributor_restores_their_lines()
    {
        // First delete
        $this->distributor->delete();
        $this->assertSoftDeleted('lines', ['id' => $this->line->id]);

        // Then restore
        $response = $this->actingAs($this->admin1)->post(route('users.restore', $this->distributor->id));
        
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $this->distributor->id, 'deleted_at' => null]);
        $this->assertDatabaseHas('lines', ['id' => $this->line->id, 'deleted_at' => null]);
    }

    public function test_admin_cannot_edit_another_admin()
    {
        $response = $this->actingAs($this->admin1)->get(route('users.edit', $this->admin2));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
