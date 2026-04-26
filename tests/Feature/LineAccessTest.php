<?php

namespace Tests\Feature;

use App\Models\Line;
use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LineAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        \Illuminate\Database\Eloquent\Model::unguard();
        
        // Setup Roles
        if (!Role::where('name', 'admin')->exists()) Role::create(['name' => 'admin']);
        if (!Role::where('name', 'موزع')->exists()) Role::create(['name' => 'موزع']);
        if (!Permission::where('name', 'manage lines')->exists()) Permission::create(['name' => 'manage lines']);

        $this->adminRole = Role::where('name', 'admin')->first();
        $this->distRole = Role::where('name', 'موزع')->first();
        $this->manageLinesPermission = Permission::where('name', 'manage lines')->first();

        // Assign permissions
        $this->distRole->permissions()->syncWithoutDetaching([$this->manageLinesPermission->id]);
        $this->adminRole->permissions()->syncWithoutDetaching([$this->manageLinesPermission->id]);

        // Setup Users
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->adminRole->id
        ]);

        $this->distributorA = User::create([
            'name' => 'Dist A',
            'email' => 'a@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->distRole->id
        ]);

        $this->distributorB = User::create([
            'name' => 'Dist B',
            'email' => 'b@test.com',
            'password' => bcrypt('password'),
            'role_id' => $this->distRole->id
        ]);

        // Create Lines
        Line::create([
            'phone_number' => '01012345678',
            'gcode' => '010',
            'provider' => 'Orange',
            'line_type' => 'prepaid',
            'distributor_id' => $this->distributorA->id
        ]);

        Line::create([
            'phone_number' => '01012345679',
            'gcode' => '010',
            'provider' => 'Orange',
            'line_type' => 'prepaid',
            'distributor_id' => $this->distributorB->id
        ]);
    }

    public function test_admin_can_see_all_lines()
    {
        $response = $this->actingAs($this->admin)->get(route('lines.all', ['phone' => '010']));
        
        $response->assertStatus(200);
        $response->assertSee('01012345678');
        $response->assertSee('01012345679');
    }

    public function test_distributor_can_only_see_their_lines()
    {
        // Distributor A
        $response = $this->actingAs($this->distributorA)->get(route('lines.all', ['phone' => '010']));
        
        $response->assertStatus(200);
        $response->assertSee('01012345678');
        $response->assertDontSee('01012345679');

        // Distributor B
        $response = $this->actingAs($this->distributorB)->get(route('lines.all', ['phone' => '010']));
        
        $response->assertStatus(200);
        $response->assertSee('01012345679');
        $response->assertDontSee('01012345678');
    }

    public function test_distributor_cannot_see_line_details_of_others()
    {
        $lineB = Line::where('distributor_id', $this->distributorB->id)->first();
        
        $response = $this->actingAs($this->distributorA)->get(route('lines.show', $lineB));
        
        // Since Global Scope is applied, the line will not be found (404) or filtered out
        $response->assertStatus(404);
    }
}
