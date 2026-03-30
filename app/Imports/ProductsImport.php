<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToCollection, WithStartRow
{
    public int $successCount = 0;
    public int $errorCount   = 0;
    public array $errors     = [];

    private static $cleared = false;

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        // مسح البيانات القديمة تلقائياً (مرة واحدة فقط)
        if (!self::$cleared) {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                ProductStock::query()->delete();
                Product::query()->delete();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                self::$cleared = true;
                Log::info('تم مسح البيانات القديمة بنجاح');
            } catch (\Exception $e) {
                Log::error('فشل مسح البيانات القديمة: ' . $e->getMessage());
            }
        }

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;

            try {
                $rowArray = $row->values()->toArray();

                $itemCode = trim($this->toString($rowArray[0] ?? ''));
                $itemName = trim($this->toString($rowArray[1] ?? ''));
                $unit     = trim($this->toString($rowArray[2] ?? ''));
                $stock    = isset($rowArray[3]) && is_numeric($this->toString($rowArray[3]))
                            ? (float) $this->toString($rowArray[3])
                            : 0.0;

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

                $type = !empty($unit) ? $unit : 'حوائط جلوريا';
                if (empty($unit)) {
                    if (mb_strpos($itemName, 'ارضيات') !== false || mb_strpos($itemName, 'أرضيات') !== false) {
                        $type = 'أرضيات جلوريا';
                    }
                }

                $size = '';
                if (preg_match('/(\d+)\s*[×xX*]\s*(\d+)/', $itemName, $matches)) {
                    $size = $matches[1] . '×' . $matches[2];
                }

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
