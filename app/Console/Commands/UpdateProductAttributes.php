<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductAttributes extends Command
{
    // اسم الأمر الذي ستكتبه في التيرمنال
    protected $signature = 'products:update-attributes';
    protected $description = 'استخراج وتحديث المقاس والفرز لجميع المنتجات بناءً على الاسم';

    public function handle()
    {
        $this->info('جاري تحديث بيانات المنتجات...');

        // جلب جميع المنتجات النشطة
        $products = Product::where('is_active', true)->get();
        $count = 0;

        foreach ($products as $product) {
            $name = $product->name;
            $updated = false;

            // --- استخراج المقاس (مثال: 32×64, 30.5×70.5) ---
            // هذه هي المعادلة السحرية (Regex) التي تبحث عن الأرقام والعلامات
            if (preg_match('/(\d+(?:\.\d+)?)\s*[x×*]\s*(\d+(?:\.\d+)?)/u', $name, $matches)) {
                $newSize = $matches[1] . '×' . $matches[2];
                if ($product->size !== $newSize) {
                    $product->size = $newSize;
                    $updated = true;
                }
            }

            // --- استخراج الفرز (أول، ثاني، ثالث، رابع) ---
            $gradeMap = [
                'أول' => ['أول', 'اول'],
                'ثاني' => ['ثاني', 'ثانى'],
                'ثالث' => ['ثالث'],
                'رابع' => ['رابع']
            ];

            foreach ($gradeMap as $standardGrade => $searchTerms) {
                foreach ($searchTerms as $term) {
                    if (mb_strpos($name, $term) !== false) {
                        if ($product->grade !== $standardGrade) {
                            $product->grade = $standardGrade;
                            $updated = true;
                        }
                        break 2; // exit both loops once found
                    }
                }
            }

            if ($updated) {
                $product->save();
                $count++;
            }
        }

        $this->info("✅ العملية تمت بنجاح! تم تحديث {$count} منتج.");
    }
}
