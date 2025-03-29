<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Восстановление пароля</title>
    <link rel="stylesheet" href="/css/styles-login.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>
<x-header />
<div class="wrapper">
    <main class="main">
        <section class="main-page">
            <div class="container">
                <div class="box-auth">
                    <div class="container-auth">
                        <div class="auth-up">
                            <h1 class="auth-title">Восстановление пароля</h1>
                        </div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <label for="email" class="label-email">Электронная почта</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            <br>
                            <label for="secret_word" class="label-secret-word">Кодовое слово</label>
                            <input type="text" id="secret_word" name="secret_word" required>
                            <br>
                            <label for="password" class="label-password">Новый пароль</label>
                            <input type="password" id="password" name="password" required>
                            <br>
                            <label for="password_confirmation" class="label-password-confirmation">Подтвердите
                                пароль</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <a href="/login" class="forgot-password">Вернуться к авторизации</a>
                            <br>
                            <button type="submit" class="button-submit">Восстановить пароль</button>
                            <br>
                            @if ($errors->any())
                                <div class="error">
                                    <ul class="error-list">
                                        @foreach ($errors->all() as $error)
                                            <li class="error-text">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-footer />
</div>
</body>

</html>