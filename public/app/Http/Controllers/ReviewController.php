<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $listing_id)
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Вы должны войти в систему, чтобы оставить отзыв.');
        }

        // Валидация данных
        $request->validate([
            'comment' => 'required|string|max:500',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Создание отзыва
        Review::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing_id,
            'comment' => $request->input('comment'),
            'rating' => $request->input('rating'),
        ]);

        return redirect()->back()->with('success', 'Ваш отзыв успешно добавлен!');
    }
}
