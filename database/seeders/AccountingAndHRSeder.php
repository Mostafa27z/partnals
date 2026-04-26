<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Models\Capital;
use App\Models\User;
use Carbon\Carbon;

class AccountingAndHRSeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        if ($users->isEmpty()) {
            $user = User::factory()->create(['name' => 'Admin Test', 'email' => 'admin@test.com', 'base_salary' => 5000]);
            $users->push($user);
        }

        // إدراج رؤوس أموال وهمية
        for ($i = 0; $i < 5; $i++) {
            DB::table('capitals')->insert([
                'amount' => rand(1000, 10000),
                'date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'description' => 'إضافة رأس مال تجريبية ' . $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // إدراج مصروفات وهمية
        $categories = ['نسريات (شاي قهوة سكر)', 'وجبات', 'مواصلات', 'صيانة'];
        for ($i = 0; $i < 10; $i++) {
            DB::table('expenses')->insert([
                'amount' => rand(50, 500),
                'category' => $categories[array_rand($categories)],
                'date' => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'),
                'description' => 'مصروف تجريبي ' . $i,
                'user_id' => $users->random()->id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // إدراج سلف وهمية للموظفين
        foreach ($users as $user) {
            DB::table('advances')->insert([
                'amount' => rand(100, 500),
                'date' => Carbon::now()->format('Y-m-d'),
                'notes' => 'سلفة تجريبية',
                'user_id' => $user->id,
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
