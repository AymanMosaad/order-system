<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('trader_name')->nullable();
            $table->string('order_number')->nullable();
            $table->string('warehouse_type')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('driver_name')->nullable();
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }

    
};
