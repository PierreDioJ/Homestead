<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model {
    use HasFactory;

    protected $fillable = ['listing_id', 'tenant_id', 'landlord_id'];

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function listing() {
        return $this->belongsTo(Listing::class);
    }

    public function tenant() {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function landlord() {
        return $this->belongsTo(User::class, 'landlord_id');
    }
}

