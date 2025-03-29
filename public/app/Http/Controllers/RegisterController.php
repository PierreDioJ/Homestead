<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Страница регистрации
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Обработка формы регистрации
    public function register(Request $request)
    {
        // Валидация данных
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:tenant,landlord', // Новая валидация роли
            'secret_word' => 'required|string|min:3',
        ]);

        // Если валидация не прошла, возвращаем ошибки
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Создание нового пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Сохраняем роль пользователя
            'secret_word' => $request->secret_word,
        ]);

        // Перенаправление на профиль после успешной регистрации
        Auth::login($user); // Вход в систему сразу после регистрации
        return redirect()->route('profile')->with('success', 'Вы успешно зарегистрированы. Добро пожаловать в личный кабинет!');
    }
}