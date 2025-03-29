<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

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

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function resetPassword(Request $request)
    {
        // Валидация
        $request->validate([
            'email' => 'required|email',
            'secret_word' => 'required|string',
            'password' => 'required|min:8|confirmed',  // Для пароля подтверждения
        ]);

        // Логируем данные запроса
        Log::info('Попытка сброса пароля:', [
            'email' => $request->email,
            'secret_word' => $request->secret_word,  // Логируем кодовое слово
        ]);

        // Поиск пользователя
        $user = User::where('email', $request->email)->first();

        // Логирование: пользователь найден
        if ($user) {
            Log::info('Пользователь найден для сброса пароля:', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        } else {
            Log::warning('Пользователь не найден для сброса пароля:', [
                'email' => $request->email,
            ]);
        }

        // Проверка кодового слова
        if ($user && $user->secret_word === $request->secret_word) {
            // Логирование: совпадение кодового слова
            Log::info('Кодовое слово совпало. Обновляем пароль для пользователя:', [
                'user_id' => $user->id,
            ]);

            // Обновляем пароль
            $user->password = bcrypt($request->password);
            $user->save();

            // Логирование успешного обновления пароля
            Log::info('Пароль обновлен для пользователя:', [
                'user_id' => $user->id,
            ]);

            // Уведомляем пользователя об успешном изменении пароля
            return redirect()->route('login')->with('status', 'Пароль успешно изменен.');
        }

        // Логирование: кодовое слово не совпало
        Log::warning('Неверное кодовое слово для сброса пароля:', [
            'email' => $request->email,
        ]);

        // Ошибка, если кодовое слово не совпадает
        return back()->withErrors([
            'secret_word' => 'Неверное кодовое слово.',
        ]);
    }



    public function register(Request $request)
    {
        // Валидация данных формы
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'secret_word' => 'required|string|min:3',  // Проверяем кодовое слово
        ]);

        // Логирование данных запроса
        Log::info('Регистрация пользователя:', [
            'name' => $request->name,
            'email' => $request->email,
            'secret_word' => $request->secret_word,  // Логируем кодовое слово
        ]);

        // Создание нового пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'secret_word' => $request->secret_word,  // Записываем кодовое слово
        ]);

        // Логирование успешной регистрации
        Log::info('Пользователь зарегистрирован:', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Автоматический вход после регистрации
        Auth::login($user);

        // Перенаправление на страницу профиля
        return redirect()->route('profile');
    }


}
