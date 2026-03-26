<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateProductTypesSeeder extends Seeder
{
    public function run()
    {
        // الأنواع الجديدة المطلوبة
        $newTypes = [
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

        // عرض الأنواع الموجودة
        $this->command->info('الأنواع الموجودة حالياً:');
        $existingTypes = DB::table('products')->distinct()->pluck('type');
        foreach ($existingTypes as $type) {
            $this->command->line('- ' . ($type ?: 'فارغ'));
        }

        // تحديث المنتجات بدون نوع
        $updated = DB::table('products')->whereNull('type')->orWhere('type', '')->update(['type' => 'حوائط جلوريا']);
        $this->command->info("✅ تم تحديث {$updated} منتج بدون نوع إلى 'حوائط جلوريا'");

        // تحديث أنواع محددة
        DB::table('products')->where('type', 'بورسلين')->update(['type' => 'بورسل']);
        DB::table('products')->where('type', 'حوائط')->update(['type' => 'حوائط جلوريا']);
        DB::table('products')->where('type', 'ارضيات')->update(['type' => 'أرضيات جلوريا']);
        DB::table('products')->where('type', 'HDC')->update(['type' => 'HDC']);
        DB::table('products')->where('type', 'UGC')->update(['type' => 'UGC']);

        $this->command->info('✅ تم تحديث الأنواع بنجاح');

        // عرض الأنواع بعد التحديث
        $this->command->info('الأنواع بعد التحديث:');
        $newTypesList = DB::table('products')->distinct()->pluck('type');
        foreach ($newTypesList as $type) {
            $this->command->line('- ' . ($type ?: 'فارغ'));
        }
    }
}
