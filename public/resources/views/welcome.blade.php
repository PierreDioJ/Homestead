<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Жилье</title>
    <link rel="stylesheet" href="/css/styles-welcome.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
    <main class="main">
        <section class="filter">
            <div class="container">
                <div class="main-filter">
                    <h1 class="title-filter">Снять жильё в России на краткосрочной основе</h1>
                    <p class="number-offers"><span class="number">{{ $listings->count() }}</span>&nbsp;предложения</p>
                    <div class="filter-form">
                        <form action="{{ route('listings.index') }}" method="get" class="form-filter">
                            <div class="form-city">
                                <label for="city" class="label-city">Где</label>
                                <input type="text" name="city" id="city" placeholder="Поиск направлений"
                                    value="{{ request('city') }}" />
                            </div>
                            <div class="form-date-check-in">
                                <label for="date-check-in" class="label-check-in">Заезд</label>
                                <input type="date" name="date-check-in" id="date-check-in"
                                    value="{{ request('date-check-in') }}" placeholder="Когда?" />
                            </div>
                            <div class="form-date-departure">
                                <label for="date-departure" class="label-departure">Отъезд</label>
                                <input type="date" name="date-departure" id="date-departure"
                                    value="{{ request('date-departure') }}" placeholder="Когда?" />
                            </div>
                            <div class="form-people">
                                <label for="people" class="label-people">Гости</label>
                                <input type="number" name="people" id="people" min="1" value="{{ request('people') }}"
                                    placeholder="Кто едет?" />
                            </div>
                            <div class="form-price">
                                <label for="max_price" class="label-price">Макс. цена</label>
                                <input type="number" name="max_price" id="max_price" min="0"
                                    value="{{ request('max_price') }}" placeholder="Цена до" />
                            </div>
                            <div class="form-submit">
                                <img class="search-filter" src="../img/search-filter.png" />
                                <input type="submit" value="" class="form-submit-filter" />
                            </div>
                        </form>
                        <p class="advantage-rent">Краткосрочная аренда жилья — идеальное решение для туристов и
                            командированных</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="offers">
            <div class="container">
                <div class="header-sort">
                    <h3 class="number-offers-find">
                        <span class="number">НАЙДЕНО ПРЕДЛОЖЕНИЙ: {{ $listings->total() ?? 0 }}</span>
                    </h3>
                    <form method="GET" action="{{ route('listings.index') }}">
    <select id="sort" name="sort" onchange="this.form.submit()">
        <option value="" disabled selected>Способ сортировки</option>
        <option value="by_rating" {{ request('sort') == 'by_rating' ? 'selected' : '' }}>По рейтингу</option>
        <option value="cheaper" {{ request('sort') == 'cheaper' ? 'selected' : '' }}>Сначала дешевле</option>
        <option value="more_expensive" {{ request('sort') == 'more_expensive' ? 'selected' : '' }}>Сначала дороже</option>
    </select>
</form>

                </div>

                <div class="listings-list">
                    <div class="listings">
                        @foreach ($listings as $listing)
                            <a href="{{ route('listings.show', $listing->id) }}" class="listing-card">
                                <div class="listing-image-wrapper">
                                    <button class="favorite-btn" data-id="{{ $listing->id }}">♡</button>
                                    <img src="{{ asset('storage/' . $listing->photo) }}" alt="Фото" class="listing-img">

                                    {{-- Проверяем условия для бейджа --}}
                                    @if ($listing->reviews_count >= 10 && $listing->average_rating >= 4.5)
                                        <div class="listing-badge">Выбор гостей</div>
                                    @endif
                                    <span class="listing-rating">
                                        <i class="icon-star"></i> &#9733;
                                        {{ number_format($listing->average_rating, 2) }}
                                    </span>
                                </div>
                                <div class="listing-info">
                                    <div class="listing-header">
                                        <h3 class="listing-title">{{ $listing->title }}</h3>
                                    </div>
                                    <p class="listing-location">{{ $listing->location }}</p>
                                    <p class="listing-details">Ближайшая свободная дата:
                                        <strong>{{ $listing->nextAvailableDate ? $listing->nextAvailableDate : 'Нет доступных дат' }}</strong>
                                    <p class="listing-price">{{ $listing->price }} ₽ / ночь</p>
                                </div>
                            </a>
                        @endforeach
                    </div>


                </div>

            </div>
        </section>
    </main>
    <x-footer />
    </div>
    <script src="/js/favorites.js" defer></script>
    <script src="{{ asset('js/sort.js') }}"></script>
</body>

</html>