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
        Schema::table('orders', function (Blueprint $table) {
            // إضافة user_id (الموظف اللي أدخل الطلبية)
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();

            // إضافة status (حالة الطلبية)
            $table->enum('status', ['جديدة', 'معالجة', 'مكتملة', 'ملغاة'])->default('جديدة')->after('notes');

            // إضافة reference_number (رقم مرجعي للـ ViewSoft)
            $table->string('reference_number')->nullable()->unique()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\User::class);
            $table->dropColumn(['status', 'reference_number']);
        });
    }
};
