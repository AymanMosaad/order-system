<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'حوائط جلوريا',
            'حوائط ايكو',
            'أرضيات جلوريا',
            'أرضيات ايكو',
            'HDC',
            'UGC',
            'بورسل',
            'PORSLIM',
            'SUPER GLOSSY 61×122.5',
            'SUPER GLOSSY 61×61'
        ];

        // عرض الأنواع
        foreach ($types as $type) {
            $this->command->info("✅ النوع: " . $type);
        }

        $this->command->info("\n📌 الأنواع الموجودة حالياً:");
        $existingTypes = DB::table('products')->distinct()->pluck('type');
        foreach ($existingTypes as $type) {
            $this->command->line("- " . ($type ?: 'فارغ'));
        }

        $this->command->warn("\n⚠️ هذا السيدر لا يقوم بتحديث البيانات تلقائياً.");
        $this->command->warn("إذا أردت تحديث الأنواع، استخدم UPDATE في Tinker.");
    }
}
