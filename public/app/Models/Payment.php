<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * Заполняемые поля (fillable)
     */
    protected $fillable = [
        'user_id',
        'listing_id',  
        'amount',
        'status',
    ];

    /**
     * Связь с пользователем (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с объявлением (Listing)
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}

