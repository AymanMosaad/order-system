<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'المدير العام',
            'email' => 'admin@gloria.com',
            'password' => Hash::make('12345678'),
            'is_admin' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ تم إنشاء مستخدم مدير بنجاح');
        $this->command->info('📧 البريد: admin@gloria.com');
        $this->command->info('🔑 كلمة المرور: 12345678');
    }
}
