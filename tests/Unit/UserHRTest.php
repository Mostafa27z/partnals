<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Advance;
use App\Models\Request;
use App\Models\Target;
use App\Models\Bonus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class UserHRTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_monthly_advances_calculates_correctly()
    {
        $user = User::factory()->create([
            'base_salary' => 5000,
        ]);

        Advance::create([
            'user_id' => $user->id,
            'amount' => 500,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => 'approved'
        ]);

        Advance::create([
            'user_id' => $user->id,
            'amount' => 1000,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => 'approved'
        ]);

        // Should be 1500
        $this->assertEquals(1500, $user->getMonthlyAdvances());
    }

    public function test_check_advance_status_returns_exceeds_when_over_salary()
    {
        $user = User::factory()->create([
            'base_salary' => 2000,
        ]);

        Advance::create([
            'user_id' => $user->id,
            'amount' => 1500,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => 'approved'
        ]);

        // Existing advance is 1500, salary is 2000. Requesting 600 more (total 2100).
        $status = $user->checkAdvanceStatus(600);

        $this->assertTrue($status['exceeds']);
        $this->assertEquals(2100, $status['current_total']);
    }

    public function test_check_advance_status_returns_false_when_within_limits()
    {
        $user = User::factory()->create([
            'base_salary' => 3000,
        ]);

        // Requesting 1000
        $status = $user->checkAdvanceStatus(1000);

        $this->assertFalse($status['exceeds']);
    }
}
