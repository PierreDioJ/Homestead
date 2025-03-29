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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор платежа
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Связь с пользователем, который сделал платеж
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade');  // Связь с объявлением, за которое был произведен платеж
            $table->decimal('amount', 8, 2);  // Сумма платежа (с двумя знаками после запятой)
            $table->enum('status', ['pending', 'completed', 'failed']);  // Статус платежа (ожидающий, завершенный, неудачный)
            $table->timestamps();  // Даты создания и обновления записи
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');  // Удаляем таблицу при откате миграции
    }
};
