<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    // Страница профиля
    public function showProfile()
{
    $user = Auth::user(); // Получаем текущего авторизованного пользователя
    $bookings = $user->bookings()->with('listing')->orderBy('created_at', 'desc')->get(); // Загружаем бронирования

    return view('profile', compact('user', 'bookings')); // Передаём бронирования в шаблон
}
    // Обновление данных профиля
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Обновляем имя и email
        $user->name = $request->name;
        $user->email = $request->email;

        // Если пароль был изменен
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save(); // Сохраняем изменения

        return redirect()->route('profile')->with('success', 'Данные обновлены успешно');
    }
}
