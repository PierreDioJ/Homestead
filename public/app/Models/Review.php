<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово заполнять.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'listing_id',
        'rating',
        'comment',
    ];

    /**
     * Связь с пользователем.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь с объявлением.
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
