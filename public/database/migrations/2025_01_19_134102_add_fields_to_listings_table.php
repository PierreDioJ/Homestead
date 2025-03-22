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
        Schema::table('listings', function (Blueprint $table) {
            $table->date('check_in_date')->nullable();   // Дата заезда
            $table->date('check_out_date')->nullable();  // Дата отъезда
            $table->unsignedInteger('guests')->default(1); // Количество гостей
            $table->string('photo')->nullable();         // Путь к фото
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['check_in_date', 'check_out_date', 'guests', 'photo']);
        });
    }
};
