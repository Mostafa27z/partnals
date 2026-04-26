<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ConditionIsActiveMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Define a temporary route for testing the middleware
        Route::middleware(['web', 'condition.is.active:test permission'])
            ->get('/_test_middleware', function () {
                return 'Passed';
            });
    }

    /** @test */
    public function admin_is_always_allowed_even_if_permission_is_inactive()
    {
        // 1. Create Admin Role and User
        $adminRole = Role::create(['name' => 'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // 2. Insert inactive permission
        DB::table('permissions')->insert([
            'name' => 'test permission',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Act as admin and visit test route
        $response = $this->actingAs($admin)->get('/_test_middleware');

        // 4. Assert response is 200 (Passed)
        $response->assertStatus(200);
        $response->assertSee('Passed');
    }

    /** @test */
    public function non_admin_is_allowed_if_permission_is_active()
    {
        // 1. Create Regular Role and User
        $userRole = Role::create(['name' => 'user']);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        // 2. Insert active permission
        DB::table('permissions')->insert([
            'name' => 'test permission',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Act as user and visit test route
        $response = $this->actingAs($user)->get('/_test_middleware');

        // 4. Assert response is 200 (Passed)
        $response->assertStatus(200);
        $response->assertSee('Passed');
    }

    /** @test */
    public function non_admin_is_blocked_if_permission_is_inactive()
    {
        // 1. Create Regular Role and User
        $userRole = Role::create(['name' => 'user']);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        // 2. Insert inactive permission
        DB::table('permissions')->insert([
            'name' => 'test permission',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Act as user and visit test route
        $response = $this->actingAs($user)->get('/_test_middleware');

        // 4. Assert response is 403 (Forbidden)
        $response->assertStatus(403);
    }

    /** @test */
    public function custom_403_page_is_rendered_on_denial()
    {
        // 1. Create Regular Role and User
        $userRole = Role::create(['name' => 'user']);
        $user = User::factory()->create(['role_id' => $userRole->id]);

        // 2. Insert inactive permission
        DB::table('permissions')->insert([
            'name' => 'test permission',
            'is_active' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Act as user and visit test route
        $response = $this->actingAs($user)->get('/_test_middleware');

        // 4. Assert custom view content (from errors.403.blade.php)
        $response->assertStatus(403);
        $response->assertSee('403');
        $response->assertSee('Access Denied');
        // Check for specific CSS or text from our premium design
        $response->assertSee('glass'); 
    }
}
