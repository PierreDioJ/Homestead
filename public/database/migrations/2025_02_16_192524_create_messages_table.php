<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade'); // Бронирование
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); // Отправитель
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade'); // Получатель
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade'); // Объявление
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
