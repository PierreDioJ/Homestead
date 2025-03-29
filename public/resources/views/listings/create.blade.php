<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Homestead | Создать объявление</title>
    <link rel="stylesheet" href="/css/styles-create-listings.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
    <main class="main-page grid--2 container">
        <div class="left-side-create">
            <h2 class="create-title">Создать объявление</h2>

            @if (session('success'))
                <div class="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="create-box">
            <form action="{{ route('listings.store') }}" method="POST" enctype="multipart/form-data">
                <h2 class="form-title">Ваше объявление</h2>
                @csrf

                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>

                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description') }}</textarea>

                <label for="location">Местоположение</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required>

                <label for="price">Цена за ночь (₽)</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" required>

                <label for="guests">Максимум гостей</label>
                <input type="number" id="guests" name="guests" value="{{ old('guests') }}" required>

                <label for="photo">Фото</label>
                <input type="file" id="photo" name="photo" accept="image/*">

                <button type="submit">Создать</button>
            </form>
        </div>
    </main>
   <x-footer />
   </div>
</body>

</html>