<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('item_code');
            $table->string('type')->nullable();
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->integer('grade1')->default(0);
            $table->integer('grade2')->default(0);
            $table->integer('grade3')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
