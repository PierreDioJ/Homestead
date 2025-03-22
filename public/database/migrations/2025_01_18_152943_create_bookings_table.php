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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор бронирования
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Связь с пользователем, который сделал бронирование
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade');  // Связь с объявлением, которое было забронировано
            $table->date('check_in_date');  // Дата заезда
            $table->date('check_out_date');  // Дата выезда
            $table->enum('status', ['pending', 'confirmed', 'canceled']);  // Статус бронирования (ожидающее, подтвержденное, отмененное)
            $table->timestamps();  // Даты создания и обновления записи
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');  // Удаляем таблицу при откате миграции
    }
};
