<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// للحفاظ على الأكواد الطويلة كنص (خصوصًا العمود A)
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * يستورد الأرصدة من ملف Excel بعناوين عربية:
 * - كود الصنف (A)
 * - اسم الصنف (B)  [اختياري]
 * - الوحدة   (C)   [اختياري]
 * - الرصيد   (D)
 */
class ProductsImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithCustomValueBinder
{
    /** عدد الصفوف التي تمت معالجتها بنجاح */
    public int $successCount = 0;

    /** عدد الصفوف التي فشلت */
    public int $errorCount = 0;

    /** رسائل الأخطاء */
    public array $errors = [];

    /**
     * إجبار القيم في عمود الأكواد على أنها نص؛
     * نفترض أن "كود الصنف" في العمود A.
     */
    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'A') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }

    /**
     * @param Collection<int, array<string, mixed>> $rows
     */
    public function collection(Collection $rows)
    {
        // الصف الأول عناوين؛ أول صف بيانات فعلي رقم 2
        $excelRow = 2;

        foreach ($rows as $row) {
            try {
                $row = (array) $row;

                // أسماء الأعمدة كما في الشيت (بعربي ومسافات)
                $itemCode = $row['كود الصنف'] ?? null;
                $itemName = $row['اسم الصنف'] ?? null; // اختياري
                $unit     = $row['الوحدة']     ?? null; // اختياري
                $stockRaw = $row['الرصيد']     ?? 0;

                // تطبيع الرصيد (يدعم "288,72")
                $stockVal = $this->toNumber($stockRaw);

                if (!$itemCode) {
                    $this->errorCount++;
                    $this->errors[] = "صف {$excelRow}: بدون (كود الصنف).";
                    $excelRow++;
                    continue;
                }

                // ابحث عن المنتج بـ item_code (أضف orWhere للباركود لو عندك عمود barcode)
                $product = Product::where('item_code', trim((string) $itemCode))->first();

                if (!$product) {
                    $this->errorCount++;
                    $this->errors[] = "صف {$excelRow}: الصنف '{$itemCode}' غير موجود في قاعدة البيانات.";
                    $excelRow++;
                    continue;
                }

                // تحديث/إنشاء رصيد المخزون
                $stock = ProductStock::where('product_id', $product->id)->first();

                if ($stock) {
                    if (method_exists($stock, 'setStock')) {
                        $stock->setStock($stockVal);
                    } else {
                        $stock->update(['current_stock' => $stockVal]);
                    }
                } else {
                    ProductStock::create([
                        'product_id'    => $product->id,
                        'current_stock' => $stockVal,
                    ]);
                }

                // (اختياري) مزامنة الاسم/الوحدة مع المنتج:
                // if ($itemName && $product->name !== $itemName) {
                //     $product->update(['name' => $itemName]);
                // }
                // if ($unit && property_exists($product, 'unit') && $product->unit !== $unit) {
                //     $product->update(['unit' => $unit]);
                // }

                $this->successCount++;
            } catch (\Throwable $e) {
                $this->errorCount++;
                $this->errors[] = "صف {$excelRow}: خطأ غير متوقع — " . $e->getMessage();
            }

            $excelRow++;
        }
    }

    /**
     * تطبيع الأرقام: تقبل "288.72" أو "288,72" وترجع float.
     */
    private function toNumber($v): float
    {
        if (is_null($v) || $v === '') {
            return 0.0;
        }

        if (is_string($v)) {
            // دعم الفاصلة العشرية الأوروبية
            $v = str_replace([' ', ','], ['', '.'], $v);
        }

        return is_numeric($v) ? (float) $v : 0.0;
    }
}
