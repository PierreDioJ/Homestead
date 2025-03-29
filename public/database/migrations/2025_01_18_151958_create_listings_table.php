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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');  // Связь с пользователем (автор объявления)
            $table->string('title');  // Заголовок объявления
            $table->text('description');  // Описание объявления
            $table->string('location');  // Местоположение объекта
            $table->decimal('price', 8, 2);  // Цена аренды
            $table->date('check_in_date');  // Дата заезда
            $table->date('check_out_date');  // Дата отъезда
            $table->integer('guests');  // Количество гостей
            $table->string('photo')->nullable();  // Фотография (если есть)
            $table->timestamps();  // Даты создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');  // Удаляем таблицу при откате миграции
    }
};

