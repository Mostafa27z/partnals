<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure core roles exist
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
        }
        if (!Role::where('name', 'موزع')->exists()) {
            Role::create(['name' => 'موزع']);
        }
    }

    public function test_only_admin_can_access_roles_index()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);

        $normalRole = Role::create(['name' => 'staff']);
        $user = User::create([
            'name' => 'Normal User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
            'role_id' => $normalRole->id
        ]);

        $this->actingAs($admin)->get(route('roles.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('roles.index'))->assertStatus(403);
    }

    public function test_admin_can_create_role()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);

        $this->actingAs($admin)->post(route('roles.store'), ['name' => 'New Role'])
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', ['name' => 'New Role']);
    }

    public function test_admin_can_update_role()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);
        
        $role = Role::create(['name' => 'Old Name']);

        $this->actingAs($admin)->put(route('roles.update', $role), ['name' => 'Updated Name'])
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', ['name' => 'Updated Name']);
    }

    public function test_admin_cannot_delete_protected_roles()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);
        
        $protectedRole = Role::where('name', 'موزع')->first();

        $this->actingAs($admin)->delete(route('roles.destroy', $protectedRole))
            ->assertSessionHas('error', __('messages.role_protected_error'));

        $this->actingAs($admin)->delete(route('roles.destroy', $adminRole))
            ->assertSessionHas('error', __('messages.role_protected_error'));

        $this->assertDatabaseHas('roles', ['id' => $protectedRole->id]);
        $this->assertDatabaseHas('roles', ['id' => $adminRole->id]);
    }

    public function test_admin_can_delete_regular_role()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);
        
        $role = Role::create(['name' => 'Temporary Role']);

        $this->actingAs($admin)->delete(route('roles.destroy', $role))
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseMissing('roles', ['name' => 'Temporary Role']);
    }

    public function test_admin_cannot_edit_protected_roles()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);
        
        $protectedRole = Role::where('name', 'موزع')->first();

        // Test edit page access
        $this->actingAs($admin)->get(route('roles.edit', $protectedRole))
            ->assertSessionHas('error', __('messages.role_edit_protected_error'));

        // Test update action
        $this->actingAs($admin)->put(route('roles.update', $protectedRole), ['name' => 'Hacker Name'])
            ->assertSessionHas('error', __('messages.role_edit_protected_error'));

        $this->assertDatabaseHas('roles', ['name' => 'موزع']);
    }
}
