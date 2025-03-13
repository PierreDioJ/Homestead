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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор отзыва
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Связь с пользователем, который оставил отзыв
            $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade');  // Связь с объявлением, к которому относится отзыв
            $table->integer('rating');  // Рейтинг от 1 до 5 (можно ограничить через валидацию)
            $table->text('comment');  // Текстовый комментарий отзыва
            $table->timestamps();  // Даты создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');  
    }
};
