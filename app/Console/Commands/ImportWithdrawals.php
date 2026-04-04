<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Withdrawal;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ImportWithdrawals extends Command
{
    protected $signature = 'import:withdrawals {file}';
    protected $description = 'Import customer withdrawals from Excel file';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $this->info('Starting withdrawals import...');

        $data = Excel::toArray([], $file);
        $rows = $data[0] ?? [];

        $stats = ['customers' => 0, 'orders' => 0, 'items' => 0, 'withdrawals' => 0, 'errors' => 0];

        foreach ($rows as $index => $row) {
            if ($index == 0 || empty($row[4]) || empty($row[5])) continue;

            try {
                $customerName = trim($row[4]);      // اسم العميل
                $orderNumber = $row[1];              // رقم الاذن
                $itemCode = $row[5];                 // كود الصنف
                $itemName = $row[6];                 // اسم الصنف
                $quantity = (float) ($row[8] ?? 0);  // الكمية
                $date = $row[2] ?? date('Y-m-d');    // التاريخ
                $nature = $row[0] ?? 'صرف مبيعات';   // طبيعة الاذن

                if ($quantity <= 0) continue;

                // البحث عن العميل أو إنشاؤه
                $customer = Customer::where('name', $customerName)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $customerName,
                        'type' => $this->getCustomerType($nature),
                    ]);
                    $stats['customers']++;
                    $this->info("Created customer: {$customerName}");
                }

                // البحث عن المنتج
                $product = Product::where('item_code', $itemCode)->first();
                if (!$product) {
                    $this->warn("Product not found: {$itemCode}");
                    continue;
                }

                // البحث عن الطلبية أو إنشاؤها
                $order = Order::where('order_number', $orderNumber)->first();
                if (!$order) {
                    $order = Order::create([
                        'order_number' => $orderNumber,
                        'customer_id' => $customer->id,
                        'customer_name' => $customerName,
                        'date' => date('Y-m-d', strtotime($date)),
                        'status' => 'مكتملة',
                        'notes' => $nature,
                    ]);
                    $stats['orders']++;
                } else {
                    $order->update(['customer_id' => $customer->id]);
                }

                // حساب الإجمالي
                $price = $product->price ?? 0;
                $discount = ($customer->discount_rate / 100) * $price;
                $unitPrice = $price - $discount;
                $total = $quantity * $unitPrice;

                // إضافة عنصر الطلبية
                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $product->id],
                    [
                        'item_code' => $itemCode,
                        'name' => $itemName,
                        'grade1' => $quantity,
                        'unit_price' => $unitPrice,
                        'discount_rate' => $customer->discount_rate,
                        'discount_amount' => $discount * $quantity,
                        'total' => $total,
                    ]
                );
                $stats['items']++;

                // ✅ إضافة المسحوبات (Withdrawal)
                Withdrawal::create([
                    'customer_id' => $customer->id,
                    'order_id' => $order->id,
                    'amount' => $total,
                    'date' => date('Y-m-d', strtotime($date)),
                    'notes' => $nature,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $stats['withdrawals']++;

            } catch (\Exception $e) {
                $stats['errors']++;
                $this->error("Error at row {$index}: " . $e->getMessage());
            }
        }

        $this->info("✅ Import completed:");
        $this->info("   Customers created: {$stats['customers']}");
        $this->info("   Orders created: {$stats['orders']}");
        $this->info("   Items added: {$stats['items']}");
        $this->info("   Withdrawals added: {$stats['withdrawals']}");
        $this->info("   Errors: {$stats['errors']}");

        return 0;
    }

    private function getCustomerType($nature)
    {
        if (str_contains($nature, 'تصدير')) return 'agent';
        if (str_contains($nature, 'محليه')) return 'dealer';
        return 'cash';
    }
}
