<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock products and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 جاري فحص المخزون المنخفض...');

        $startTime = microtime(true);

        Product::checkAllProductsLowStock();

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        $this->info('✅ تم الانتهاء من فحص المخزون المنخفض في ' . $duration . ' ثانية');

        return Command::SUCCESS;
    }
}
