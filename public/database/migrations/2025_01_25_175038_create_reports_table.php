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
        Schema::create('reports', function (Blueprint $table) {
            $table->id(); // Идентификатор
            $table->unsignedBigInteger('listing_id'); // ID объявления
            $table->string('reason'); // Причина жалобы
            $table->text('details')->nullable(); // Дополнительные детали
            $table->timestamps(); // Поля created_at и updated_at

            // Внешний ключ для связи с таблицей объявлений (при наличии)
            $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
