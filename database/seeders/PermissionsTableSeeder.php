<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'manage dashboard',
            'manage permissions',
            'edit company details',
            'manage customers',
            'manage plans',
            'manage lines',
            'manage invoices',
            'manage requests',
            'manage users',
            'manage accounting',
            'manage hr',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission],
                ['is_active' => true, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
