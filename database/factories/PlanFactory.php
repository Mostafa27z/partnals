<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        $providers = ['Vodafone', 'Orange', 'Etisalat', 'WE'];
        $provider = $this->faker->randomElement($providers);
        
        $planNames = [
            'Vodafone' => ['فليكس 35', 'فليكس 60', 'فليكس 100', 'ريد كلاسيك', 'ريد هيم'],
            'Orange' => ['فري ماكس 35', 'فري ماكس 50', 'إيجل 100', 'إيجل 250'],
            'Etisalat' => ['حكاية 35', 'حكاية 50', 'حكاية ميكس 70', 'إميرالد 250'],
            'WE' => ['كنترول تظبيط 35', 'كنترول تظبيط 65', 'إنديغو 150'],
        ];

        $name = $this->faker->randomElement($planNames[$provider]);
        $price = $this->faker->numberBetween(35, 300);

        return [
            'name' => $name,
            'price' => $price,
            'provider' => $provider,
            'provider_price' => $price * 0.8,
            'type' => $this->faker->randomElement(['prepaid', 'postpaid']),
            'plan_code' => $this->faker->unique()->numerify('PLAN-####'),
            'penalty' => $this->faker->numberBetween(10, 50),
        ];
    }
}
