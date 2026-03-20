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
        Schema::table('products', function (Blueprint $table) {
            // إضافة type إذا كانت مش موجودة
            if (!Schema::hasColumn('products', 'type')) {
                $table->string('type')->nullable()->after('item_code');
            }

            // إضافة is_active
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
