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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade'); // ID арендодателя
            $table->timestamp('expires_at')->nullable(); // Время, когда бронирование истекает (15 минут)
            $table->string('payment_receipt')->nullable(); // Путь к чеку об оплате
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['landlord_id', 'expires_at', 'payment_receipt']);
        });
    }
};
