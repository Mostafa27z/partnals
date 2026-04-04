<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Plan;
use App\Models\User;

class LineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $gcodes = ['010', '011', '012', '015'];
        $providers = [
            '010' => 'Vodafone',
            '011' => 'Etisalat',
            '012' => 'Orange',
            '015' => 'WE',
        ];

        $gcode = $this->faker->randomElement($gcodes);
        $provider = $providers[$gcode] ?? 'Vodafone';

        return [
            'gcode'             => $gcode,
            'phone_number'      => $this->faker->unique()->numerify('########'), // 8 digits (total 11 with gcode)
            'second_phone'      => $this->faker->optional()->numerify('01#########'),
            'provider'          => $provider,
            'status'            => $this->faker->randomElement(['active', 'inactive', 'suspended', 'p-suspended']),
            'offer_name'        => $this->faker->randomElement(['عرض خاص', 'باقة سوبر', 'باقة متميزة', 'عرض العيد']),
            'branch_name'       => $this->faker->city() . ' الرئيسي',
            'employee_name'     => $this->faker->name(),
            'line_type'         => $this->faker->randomElement(['prepaid', 'postpaid']),
            'plan_id'           => Plan::inRandomOrder()->where('provider', $provider)->first()?->id ?? Plan::inRandomOrder()->first()?->id,
            'package'           => $this->faker->randomElement(['سوشيال', 'فليكس', 'بلس', 'ميجاز']),
            'payment_date'      => $this->faker->date(),
            'last_invoice_date' => $this->faker->date(),
            'notes'             => $this->faker->realText(50),
            'added_by'          => User::inRandomOrder()->first()?->id,
        ];
    }
}
