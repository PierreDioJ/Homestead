<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Атрибуты, которые можно массово заполнять.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'secret_word',
    ];

    /**
     * Атрибуты, скрытые при сериализации.
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Преобразования типов для атрибутов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Проверяет, является ли пользователь арендодателем.
     *
     * @return bool
     */
    public function isLandlord()
    {
        return $this->role === 'landlord';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Проверяет, является ли пользователь арендатором.
     *
     * @return bool
     */
    public function isTenant()
    {
        return $this->role === 'tenant';
    }

    public function favorites()
    {
        return $this->belongsToMany(Listing::class, 'favorites')->withTimestamps();
    }

    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    // app/Models/User.php
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    
}