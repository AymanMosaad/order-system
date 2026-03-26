<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تعديل جدول order_items
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('grade1', 10, 2)->change();
            $table->decimal('grade2', 10, 2)->change();
            $table->decimal('grade3', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
        });

        // تعديل جدول product_stocks
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('current_stock', 10, 2)->change();
            $table->decimal('min_stock', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('grade1')->change();
            $table->integer('grade2')->change();
            $table->integer('grade3')->change();
            $table->integer('total')->change();
        });

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->integer('current_stock')->change();
            $table->integer('min_stock')->change();
        });
    }
};
