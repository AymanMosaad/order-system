<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LowStockExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function collection()
    {
        return $this->products;
    }

    public function headings(): array
    {
        return [
            'كود الصنف',
            'اسم الصنف',
            'النوع',
            'الرصيد الحالي',
            'الحد الأدنى',
            'العجز',
            'الحالة'
        ];
    }

    public function map($product): array
    {
        $currentStock = $product->getCurrentStock();
        $minStock = $product->stock?->min_stock ?? 50;
        $shortage = $minStock - $currentStock;
        $status = $currentStock <= 0 ? 'منفذ' : 'منخفض';

        return [
            $product->item_code,
            $product->name,
            $product->type ?? '-',
            $currentStock,
            $minStock,
            $shortage,
            $status
        ];
    }
}
