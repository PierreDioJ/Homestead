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
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // Уникальный идентификатор
            $table->string('name');  // Имя пользователя
            $table->string('email')->unique();  // Уникальный email
            $table->string('password');  // Пароль
            $table->timestamps();  // Даты создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');  // Удаляем таблицу при откате миграции
    }
};
