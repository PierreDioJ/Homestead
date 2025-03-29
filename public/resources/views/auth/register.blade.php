<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Регистрация</title>
    <link rel="stylesheet" href="/css/styles-register.css">
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
                                <h1 class="auth-title">Создать аккаунт</h1>
                            </div>
                            <div class="auth-middle">
                                <div class="auth-welcome-text">
                                    <p class="auth-welcome">Добро пожаловать в Homestead</p>
                                </div>
                                <form method="POST" action="{{ url('/register') }}">
                                    @csrf
                                    <label for="name" class="label-name">Имя</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                                    <br>
                                    <label for="email" class="label-email">Электронная почта</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                                    <br>
                                    <label for="secret_word" class="label-secret-word">Кодовое слово (для восстановления пароля)</label>
                                    <input type="text" id="secret_word" name="secret_word"
                                        value="{{ old('secret_word') }}" required>
                                    <br>
                                    <label for="password" class="label-password">Пароль</label>
                                    <input type="password" id="password" name="password" required>
                                    <br>
                                    <label for="password_confirmation" class="label-password-confirmation">Подтвердите
                                        пароль</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        required>
                                    <br>
                                    <label for="role" class="label-role">Зарегистрироваться как:</label>
                                    <select id="role" name="role" required>
                                        <option value="" disabled selected>Выберите роль</option>
                                        <option value="tenant">Арендатор</option>
                                        <option value="landlord">Арендодатель</option>
                                    </select>
                                    <br>
                                    <button type="submit" class="button-submit">Зарегистрироваться</button>
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
                </div>
            </section>
        </main>
        <x-footer />
    </div>
</body>

</html>