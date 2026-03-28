<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection, WithStartRow
{
    public int $successCount = 0;
    public int $errorCount   = 0;
    public array $errors     = [];

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            try {
                // ===== الإصلاح: تحويل Collection للـ array بشكل صحيح =====
                $rowArray = $row->values()->toArray();

                $itemCode = trim($this->toString($rowArray[0] ?? ''));
                $itemName = trim($this->toString($rowArray[1] ?? ''));
                $unit     = trim($this->toString($rowArray[2] ?? ''));
                $stock    = isset($rowArray[3]) && is_numeric($this->toString($rowArray[3]))
                            ? (float) $this->toString($rowArray[3])
                            : 0.0;

                // تخطي الصفوف الفاضية
                if (empty($itemCode) && empty($itemName)) {
                    continue;
                }

                if (empty($itemCode)) {
                    $this->errors[] = "صف {$rowNum}: كود الصنف مطلوب";
                    $this->errorCount++;
                    continue;
                }

                if (empty($itemName)) {
                    $this->errors[] = "صف {$rowNum}: اسم الصنف مطلوب";
                    $this->errorCount++;
                    continue;
                }

                // تحديد النوع من عمود الوحدة أو الاسم
                $type = !empty($unit) ? $unit : 'حوائط جلوريا';
                if (empty($unit)) {
                    if (mb_strpos($itemName, 'ارضيات') !== false || mb_strpos($itemName, 'أرضيات') !== false) {
                        $type = 'أرضيات جلوريا';
                    }
                }

                // استخراج المقاس من الاسم
                $size = '';
                if (preg_match('/(\d+)\s*[×xX*]\s*(\d+)/', $itemName, $matches)) {
                    $size = $matches[1] . '×' . $matches[2];
                }

                // إنشاء أو تحديث المنتج
                $product = Product::updateOrCreate(
                    ['item_code' => $itemCode],
                    [
                        'name'      => $itemName,
                        'type'      => $type,
                        'size'      => $size ?: null,
                        'color'     => null,
                        'price'     => 0,
                        'is_active' => true,
                    ]
                );

                // إنشاء أو تحديث الرصيد
                ProductStock::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'current_stock' => $stock,
                        'min_stock'     => 0,
                    ]
                );

                $this->successCount++;

            } catch (\Throwable $e) {
                $msg = "صف {$rowNum}: " . $e->getMessage();
                $this->errors[] = $msg;
                $this->errorCount++;
                Log::error('ProductsImport error: ' . $msg);
            }
        }
    }

    /**
     * تحويل أي قيمة لنص بأمان
     */
    private function toString($value): string
    {
        if (is_null($value)) return '';

        if (is_array($value)) {
            $value = reset($value);
            return $this->toString($value);
        }

        if (is_object($value)) {
            if (method_exists($value, '__toString')) return (string) $value;
            if (method_exists($value, 'getPlainText')) return $value->getPlainText();
            return '';
        }

        return (string) $value;
    }
}
