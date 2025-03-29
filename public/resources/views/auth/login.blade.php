<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Авторизация</title>
    <link rel="stylesheet" href="/css/styles-login.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
    <main class="main">
        <section class="main-page">
            <div class="container">
                <div class="box-auth">
                    <div class="container-auth">
                        <div class="auth-up">
                            <h1 class="auth-title">Войти в аккаунт</h1>
                        </div>
                        <div class="auth-middle">
                            <div class="auth-welcome-text">
                                <p class="auth-welcome">Добро пожаловать в Homestead</p>
                            </div>
                            <form method="POST" action="{{ url('/login') }}">
                                @csrf
                                <label for="email" class="label-email">Электронная почта</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                                <br>
                                <label for="password" class="label-password">Пароль:</label>
                                <input type="password" id="password" name="password" required>
                                <a href="/forgot-password"class="forgot-password">Забыли пароль?</a>
                                <br>
                                <button type="submit" class="button-submit">Продолжить</button>
                                <br>
                                <p class="or">или</p>
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
                            <form action="/register" target="_self">
                                <button type="submit" class="button-reg">Зарегистрироваться</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <x-footer />
    </div>
</body>

</html>