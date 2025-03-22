<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ListingController extends Controller
{


    // Показываем все объявления с фильтрацией
    public function index(Request $request)
    {
        $query = Listing::withCount('reviews')->withAvg('reviews', 'rating');

        if ($request->filled('city')) {
            $query->where('location', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->filled('people')) {
            $query->where('guests', '>=', $request->input('people'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
        // Обрабатываем сортировку
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'by_rating':
                    $query->orderByDesc('reviews_avg_rating'); // Сортировка по рейтингу
                    break;
                case 'cheaper':
                    $query->orderBy('price', 'asc'); // Сначала дешевле
                    break;
                case 'more_expensive':
                    $query->orderBy('price', 'desc'); // Сначала дороже
                    break;
            }
        }


        // Получаем все объявления без пагинации, чтобы можно было их отфильтровать
        $listings = $query->get();

        // Фильтрация объявлений по доступным датам
        $filteredListings = $listings->filter(function ($listing) use ($request) {
            $bookedDates = Booking::where('listing_id', $listing->id)
                ->get(['check_in_date', 'check_out_date'])
                ->map(function ($booking) {
                    $dates = [];
                    $start = \Carbon\Carbon::parse($booking->check_in_date);
                    $end = \Carbon\Carbon::parse($booking->check_out_date);

                    while ($start <= $end) {
                        $dates[] = $start->format('Y-m-d');
                        $start->addDay();
                    }
                    return $dates;
                })
                ->flatten()
                ->toArray();

            // Определяем ближайшую доступную дату
            $today = now()->format('Y-m-d');
            $nextAvailableDate = null;

            for ($i = 0; $i < 365; $i++) {
                $dateToCheck = now()->addDays($i)->format('Y-m-d');
                if (!in_array($dateToCheck, $bookedDates) && $dateToCheck > $today) {
                    $nextAvailableDate = $dateToCheck;
                    break;
                }
            }

            $listing->nextAvailableDate = $nextAvailableDate;

            // Фильтрация по дате заезда и отъезда
            $checkIn = $request->input('date-check-in');
            $checkOut = $request->input('date-departure');

            if ($checkIn && in_array($checkIn, $bookedDates)) {
                return false; // Если дата заезда занята — исключаем объявление
            }

            if ($checkOut && in_array($checkOut, $bookedDates)) {
                return false; // Если дата отъезда занята — исключаем объявление
            }

            return true; // Оставляем только те объявления, где выбранные даты свободны
        });

        // Пагинация вручную, так как отфильтрованный список уже в коллекции
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10; // Количество объявлений на страницу
        $currentPageItems = $filteredListings->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedListings = new LengthAwarePaginator(
            $currentPageItems,
            $filteredListings->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('welcome', ['listings' => $paginatedListings]);
    }

    // Отображаем конкретное объявление с отзывами и ближайшей доступной датой
    public function show($id)
    {
        $listing = Listing::findOrFail($id);

        // Получаем список всех забронированных дат для этого объявления
        $bookedDates = Booking::where('listing_id', $listing->id)
            ->get(['check_in_date', 'check_out_date'])
            ->map(function ($booking) {
                $dates = [];
                $start = \Carbon\Carbon::parse($booking->check_in_date);
                $end = \Carbon\Carbon::parse($booking->check_out_date);

                // Генерация всех дат между check_in_date и check_out_date
                while ($start <= $end) {
                    $dates[] = $start->format('Y-m-d');
                    $start->addDay();
                }
                return $dates;
            })
            ->flatten()
            ->toArray();

        // Определяем ближайшую свободную дату
        $today = now()->format('Y-m-d');
        $nextAvailableDate = null;

        for ($i = 0; $i < 365; $i++) {
            $dateToCheck = now()->addDays($i)->format('Y-m-d');
            if (!in_array($dateToCheck, $bookedDates) && $dateToCheck > $today) {
                $nextAvailableDate = $dateToCheck;
                break;
            }
        }

        return view('listings.show', compact('listing', 'bookedDates', 'nextAvailableDate'));
    }
    // Определение ближайшей доступной даты бронирования
    private function getNearestAvailableDate($listingId)
    {
        $today = Carbon::today();

        // Получаем все забронированные даты для объявления
        $bookedDates = Booking::where('listing_id', $listingId)
            ->where('check_out_date', '>=', $today)
            ->orderBy('check_out_date')
            ->pluck('check_out_date');

        // Если объявление вообще не забронировано, ближайшая дата — сегодня
        if ($bookedDates->isEmpty()) {
            return $today->toDateString();
        }

        // Ищем ближайшую свободную дату
        foreach ($bookedDates as $date) {
            $nextAvailable = Carbon::parse($date)->addDay();
            if (!$bookedDates->contains($nextAvailable->toDateString())) {
                return $nextAvailable->toDateString();
            }
        }

        // Если не нашли, значит ближайшая свободная дата через 1 день после последнего бронирования
        return Carbon::parse($bookedDates->last())->addDay()->toDateString();
    }

    // Форма для создания нового объявления
    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->isLandlord()) {
            abort(403, 'У вас нет доступа к созданию объявлений.');
        }

        // Проверяем лимит объявлений
        if ($user->listings()->count() >= 2) {
            return redirect()->route('profile')->with('error', 'Вы не можете создать больше двух объявлений.');
        }

        return view('listings.create');
    }

    // Сохранение нового объявления
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isLandlord()) {
            abort(403, 'У вас нет доступа к созданию объявлений.');
        }

        // Проверяем лимит объявлений
        if ($user->listings()->count() >= 2) {
            return redirect()->back()->with('error', 'Вы достигли лимита в 2 объявления.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'guests' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photos', 'public') : null;

        Listing::create([
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'location' => $request->input('location'),
            'price' => $request->input('price'),
            'guests' => $request->input('guests'),
            'photo' => $photoPath,
        ]);

        return redirect()->route('listings.index')->with('success', 'Объявление успешно создано!');
    }

    // Форма редактирования объявления
    public function edit($id)
    {
        $listing = Listing::findOrFail($id);
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого объявления.');
        }

        return view('listings.edit', compact('listing'));
    }

    // Обновление объявления
    public function update(Request $request, $id)
    {
        $listing = Listing::findOrFail($id);
        if ($listing->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав на редактирование этого объявления.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'guests' => 'required|integer|min:1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($listing->photo) {
                Storage::disk('public')->delete($listing->photo);
            }
            $listing->photo = $request->file('photo')->store('photos', 'public');
        }

        $listing->update($request->only(['title', 'description', 'location', 'price', 'guests']));

        return redirect()->route('profile')->with('success', 'Объявление успешно обновлено!');
    }

    // Удаление объявления
    public function destroy($id)
    {
        $listing = Listing::findOrFail($id);

        if ($listing->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав на удаление этого объявления.');
        }

        if ($listing->photo) {
            Storage::disk('public')->delete($listing->photo);
        }

        $listing->delete();

        return redirect()->route('profile')->with('success', 'Объявление успешно удалено!');
    }

    // Добавление отзыва
    public function addReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $listing = Listing::findOrFail($id);

        Review::create([
            'user_id' => auth()->id(),
            'listing_id' => $listing->id,
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', 'Ваш отзыв успешно добавлен!');
    }

    public function delete($id)
    {
        $listing = Listing::findOrFail($id);

        // Удаляем фото из хранилища, если оно есть
        if ($listing->photo) {
            Storage::delete($listing->photo);
        }

        $listing->delete();

        return redirect()->back()->with('success', 'Объявление удалено.');
    }

}
