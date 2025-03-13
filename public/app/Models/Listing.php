<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Listing extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово заполнять.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'location',
        'guests',
        'photo',
    ];

    // Связь с отзывами
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Метод для получения среднего рейтинга
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    // Метод для подсчета количества отзывов
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }


    /**
     * Связь с бронированиями.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Связь с пользователем, который создал объявление.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получение полного URL для фотографии.
     *
     * @return string|null
     */
    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    /**
     * Проверка доступности по заданным датам.
     *
     * @param string $startDate
     * @param string $endDate
     * @return bool
     */
    public function isAvailable(string $startDate, string $endDate): bool
    {
        return $this->check_in_date <= $startDate && $this->check_out_date >= $endDate;
    }

    /**
     * Получение всех доступных бронирований.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function availableBookings()
    {
        return $this->bookings()->where('status', 'available')->get();
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getNearestAvailableDate()
    {
        $today = Carbon::today();

        // Ищем ближайшую дату, которая не забронирована
        $bookedDates = Booking::where('listing_id', $this->id)
            ->where('check_out_date', '>=', $today)
            ->pluck('check_in_date')
            ->toArray();

        $nearestDate = $today;

        while (in_array($nearestDate->toDateString(), $bookedDates)) {
            $nearestDate->addDay(); // Ищем следующую свободную дату
        }

        return $nearestDate->toDateString();
    }
}
