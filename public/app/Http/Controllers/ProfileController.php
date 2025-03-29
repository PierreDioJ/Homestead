<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Отображение профиля пользователя.
     */
    public function index()
    {
        $user = Auth::user();
    
        // Получаем объявления, если пользователь арендодатель
        $listings = $user->isLandlord()
            ? Listing::where('user_id', $user->id)->get()
            : collect(); // Пустая коллекция для неарендодателей
    
        // Получаем бронирования пользователя
        $bookings = $user->bookings()->with('listing')->orderBy('created_at', 'desc')->get();
    
        return view('profile', compact('user', 'listings', 'bookings')); // Передаём бронирования
    }
    

    public function show()
{
    $user = auth()->user();

    $bookings = $user->bookings()
        ->with('listing')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('profile', [
        'user' => $user,
        'listings' => $user->listings,
        'bookings' => $bookings, // Передаём в представление
    ]);
}

}
