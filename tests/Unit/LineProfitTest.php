<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Line;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LineProfitTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_days_in_month_returns_correct_number()
    {
        $line = new Line();
        $this->assertEquals(28, $line->getDaysInMonth(2, 2026));
        $this->assertEquals(31, $line->getDaysInMonth(1, 2026));
        $this->assertEquals(30, $line->getDaysInMonth(4, 2026));
    }

    public function test_daily_cost_calculates_correctly_based_on_provider_price()
    {
        $plan = Plan::create([
            'name' => 'F60 Test',
            'provider' => 'Vodafone',
            'price' => 100,
            'provider_price' => 60
        ]);

        $line = Line::factory()->create([
            'plan_id' => $plan->id,
            'system_type' => 'F60'
        ]);

        // Daily cost for April (30 days) should be 60 / 30 = 2.0
        $this->assertEquals(2.0, $line->getDailyCost(4, 2026));
    }

    public function test_calculate_profit_for_new_sale()
    {
        $plan = Plan::create([
            'name' => 'F60 Test',
            'provider' => 'Vodafone',
            'price' => 100,
            'provider_price' => 60
        ]);

        $line = Line::factory()->create([
            'plan_id' => $plan->id,
            'system_type' => 'F60',
            'attached_at' => '2026-04-10', // Sold on 10th of April
            'sale_price' => 200 // Sold for 200 EGP
        ]);

        // Profit for April is revenue (plan price) - cost (provider price)
        // Profit = 100 - 60 = 40
        $this->assertEquals(40, $line->calculateProfit(4, 2026));
    }

    public function test_calculate_profit_for_existing_line()
    {
        $plan = Plan::create([
            'name' => 'F60 Test',
            'provider' => 'Vodafone',
            'price' => 100,
            'provider_price' => 60
        ]);

        $line = Line::factory()->create([
            'plan_id' => $plan->id,
            'system_type' => 'F60',
            'attached_at' => '2026-03-01', // Sold in previous month
        ]);

        // Profit for April is revenue (plan price) - cost (provider price)
        // Profit = 100 - 60 = 40
        $this->assertEquals(40, $line->calculateProfit(4, 2026));
    }
}
