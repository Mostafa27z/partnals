<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company() . ' لخدمات المحمول',
            'company_description' => $this->faker->realText(100),
            'company_logo' => 'logos/default.png',
            'email_activation' => $this->faker->safeEmail(),
            'active_username' => $this->faker->userName(),
            'active_password' => 'password',
            'active_port' => '587',
            'suspension_penalty_days' => 3,
            'allowed_suspension_days' => 7,
            'email_problem' => $this->faker->safeEmail(),
            'problem_username' => $this->faker->userName(),
            'problem_password' => 'password',
            'problem_port' => '587',
            'smtp_configuration' => 'smtp.gmail.com',
            'portal_username' => $this->faker->userName(),
            'portal_password' => 'password',
        ];
    }
}
