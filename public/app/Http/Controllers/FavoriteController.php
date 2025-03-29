<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // ✅ Добавить или удалить объявление из избранного
    public function toggleFavorite($id)
    {
        if (!Auth::check()) {
            return response()->json(['redirect' => route('login')], 401); // 🔄 Редирект, если неавторизован
        }

        $listing = Listing::findOrFail($id);
        $user = Auth::user();

        if ($user->favorites()->where('listing_id', $id)->exists()) {
            $user->favorites()->detach($id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->attach($id);
            return response()->json(['status' => 'added']);
        }
    }

    public function getFavorites()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $favorites = Auth::user()->favorites()->with('user')->get();

        return response()->json($favorites);
    }
}
