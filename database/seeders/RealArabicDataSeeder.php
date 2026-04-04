<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Customer;
use App\Models\Line;
use Illuminate\Support\Facades\Hash;

class RealArabicDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure we have an Admin and some staff
        User::firstOrCreate(
            ['email' => 'admin@partners.com'],
            [
                'name' => 'المدير العام',
                'password' => Hash::make('password'),
                'role_id' => 1,
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@partners.com'],
            [
                'name' => 'موظف مبيعات',
                'password' => Hash::make('password'),
                'role_id' => 4, // مدير مبيعات (based on RolesTableSeeder)
            ]
        );

        // 2. Create Companies
        Company::factory()->count(3)->create();

        // 3. Create Plans for each provider
        $providers = ['Vodafone', 'Orange', 'Etisalat', 'WE'];
        foreach ($providers as $provider) {
            Plan::factory()->count(5)->create(['provider' => $provider]);
        }

        // 4. Create Customers and their Lines
        Customer::factory()->count(20)->create()->each(function ($customer) {
            // Each customer has 1 to 3 lines
            Line::factory()->count(rand(1, 3))->create([
                'customer_id' => $customer->id,
                'added_by' => User::inRandomOrder()->first()->id,
            ]);
        });

        $this->command->info('Arabic sample data seeded successfully!');
    }
}
