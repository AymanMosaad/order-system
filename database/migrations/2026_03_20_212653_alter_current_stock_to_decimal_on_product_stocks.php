<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('current_stock', 15, 2)->default(0)->change();
            // min_stock يفضل يظل integer عادي
        });
    }

    public function down(): void
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->integer('current_stock')->default(0)->change();
        });
    }
};
