<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Показать страницу входа
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработать вход в систему
     */
    public function login(Request $request)
    {
        // Валидация данных формы
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Попытка входа
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Успешный вход
            return redirect()->intended('/profile');  // Перенаправление на страницу, на которую пользователь хотел попасть
        }

        // Неверные данные
        return back()->withErrors([
            'email' => 'Неверные данные для входа.',
        ]);
    }

    /**
     * Выход из системы
     */
    public function logout()
    {
        // Выполнение выхода
        Auth::logout();

        // Очистка сессии
        session()->flush();

        // Перенаправление на страницу после выхода
        return redirect()->route('login');
    }
}
