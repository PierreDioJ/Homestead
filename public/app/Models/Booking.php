<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Заполняемые поля (fillable)
     */
    protected $fillable = [
        'user_id',
        'listing_id',
        'check_in_date',
        'check_out_date',
        'status',
        'landlord_id',
        'expires_at',
        'payment_receipt',
        'total_price',
        'guests',  
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

    /**
     * Связь с платежами (Payment)
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
