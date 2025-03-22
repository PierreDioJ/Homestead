<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homestead | Редактировать объявление</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/css/styles-general.css">
    <link rel="stylesheet" href="/css/styles-edit.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
    <main class="main-page container">
        <div class="left-side-edit">
            <h2 class="edit-title">Редактировать объявление</h2>
        </div>
        <div class="right-side-edit">

            @if ($errors->any())
                <div class="error">
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li class="error-text">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('listings.update', $listing->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h2 class="form-title">Ваше объявление</h2>
                <label for="title">Заголовок</label>
                <input type="text" id="title" name="title" value="{{ old('title', $listing->title) }}" required>

                <label for="description">Описание</label>
                <textarea id="description" name="description" rows="4"
                    required>{{ old('description', $listing->description) }}</textarea>

                <label for="location">Местоположение</label>
                <input type="text" id="location" name="location" value="{{ old('location', $listing->location) }}"
                    required>

                <label for="price">Цена за ночь</label>
                <input type="number" id="price" name="price" value="{{ old('price', $listing->price) }}" required>

                <label for="guests">Количество гостей</label>
                <input type="number" id="guests" name="guests" value="{{ old('guests', $listing->guests) }}" required>

                <label for="photo">Фото</label>
                <input type="file" id="photo" name="photo">


                <button type="submit" class="btn-submit">Сохранить изменения</button>
            </form>
        </div>
    </main>
    <x-footer />
    </div>
</body>

</html>