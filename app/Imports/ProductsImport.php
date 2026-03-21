<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * يستورد الأصناف + الأرصدة من Excel
 * الآن يدعم إنشاء الصنف لو مش موجود (updateOrCreate)
 */
class ProductsImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithCustomValueBinder
{
    public int $successCount = 0;
    public int $errorCount = 0;
    public array $errors = [];

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'A') {
            $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
            return true;
        }
        return parent::bindValue($cell, $value);
    }

    public function collection(Collection $rows)
    {
        $excelRow = 2;

        foreach ($rows as $row) {
            try {
                $row = (array) $row;

                $itemCode = trim((string) ($row['كود الصنف'] ?? $row[0] ?? ''));
                $itemName = $row['اسم الصنف'] ?? $row[1] ?? 'غير محدد';
                $unit     = $row['الوحدة'] ?? $row[2] ?? null;
                $stockRaw = $row['الرصيد'] ?? $row[3] ?? 0;

                $stockVal = $this->toNumber($stockRaw);

                if (!$itemCode) {
                    $this->errors[] = "صف {$excelRow}: بدون كود الصنف";
                    $this->errorCount++;
                    $excelRow++;
                    continue;
                }

                // === التعديل المهم: يخلق الصنف لو مش موجود ===
                $product = Product::updateOrCreate(
                    ['item_code' => $itemCode],
                    [
                        'name'  => $itemName,
                        'type'  => $unit ?? 'غير محدد',
                        // أضف هنا أي حقل إضافي موجود في موديل Product (color, size...)
                    ]
                );

                // تحديث أو إنشاء الرصيد
                ProductStock::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'current_stock' => $stockVal,
                        'min_stock'     => 0, // يمكن تغييره بعدين
                    ]
                );

                $this->successCount++;
            } catch (\Throwable $e) {
                $this->errors[] = "صف {$excelRow}: خطأ — " . $e->getMessage();
                $this->errorCount++;
            }
            $excelRow++;
        }
    }

    private function toNumber($v): float
    {
        if (is_null($v) || $v === '') return 0.0;
        if (is_string($v)) $v = str_replace([' ', ','], ['', '.'], $v);
        return is_numeric($v) ? (float) $v : 0.0;
    }
}
