<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            ['name' => 'Vodafone', 'invoice_day' => 10],
            ['name' => 'Orange',   'invoice_day' => 15],
            ['name' => 'Etisalat', 'invoice_day' => 1],
            ['name' => 'WE',       'invoice_day' => 1],
        ];

        foreach ($providers as $provider) {
            \App\Models\Provider::updateOrCreate(['name' => $provider['name']], $provider);
        }
    }
}
