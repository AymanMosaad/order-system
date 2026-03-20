<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // إضافة product_id (ربط بـ Products table)
            $table->foreignId('product_id')->nullable()->after('order_id')->constrained('products')->cascadeOnDelete();

            // إضافة item_code2 و item_code3
            $table->string('item_code2')->nullable()->after('item_code');
            $table->string('item_code3')->nullable()->after('item_code2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Product::class);
            $table->dropColumn(['item_code2', 'item_code3']);
        });
    }
};
