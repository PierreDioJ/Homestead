<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /**
     * Разрешённые для массового заполнения поля.
     */
    protected $fillable = [
        'listing_id', // ID объявления
        'reason',     // Причина жалобы
        'details',    // Дополнительные детали
    ];

    /**
     * Определение связи с моделью Listing.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
