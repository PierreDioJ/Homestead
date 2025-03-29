<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Report;
use App\Models\Review;


class AdminController extends Controller
{
    public function __construct()
    {
        // Убираем middleware для проверки, вместо этого будем проверять роль в каждом методе
    }

    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('welcome')->with('error', 'У вас нет доступа к админ-панели.');
        }

        $totalUsers = User::count();
        $totalListings = Listing::count();
        $totalBookings = Booking::count();
        $totalPayments = Payment::sum('amount');

        return view('admin.admin', compact('totalUsers', 'totalListings', 'totalBookings', 'totalPayments'));
    }

    public function listings()
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Нет доступа!');
            }

            $listings = Listing::all();

            return view('admin.listings', compact('listings'));
        } catch (\Exception $e) {
            \Log::error('Ошибка загрузки объявлений: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка загрузки объявлений!');
        }
    }

    public function updateListing(Request $request, Listing $listing)
    {
        // Проверяем роль пользователя
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'У вас нет доступа.'], 403);
        }

        // Валидируем данные
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        // Обновляем объявление
        $listing->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json(['success' => 'Объявление обновлено.']);
    }


    public function deleteListing(Listing $listing)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'У вас нет доступа.'], 403);
        }

        $listing->delete();

        return response()->json(['success' => 'Объявление удалено.']);
    }

    public function bookings()
    {
        $bookings = Booking::with('user', 'listing')->get();

        return view('admin.bookings', compact('bookings'));
    }

    public function updateBooking(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        $booking = Booking::findOrFail($id);

        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'status' => 'required|in:Ожидание,Оплачено,Отменено',
        ]);

        $booking->update([
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Бронирование обновлено.']);
    }

    public function deleteBooking($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['success' => 'Бронирование удалено.']);
    }

    public function payments()
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect()->route('admin.dashboard')->with('error', 'Нет доступа!');
            }

            $payments = Payment::all();

            \Log::info('Платежи загружены: ', ['count' => $payments->count()]);

            return view('admin.payments', compact('payments'));

        } catch (\Exception $e) {
            \Log::error('Ошибка загрузки платежей: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка загрузки платежей!');
        }
    }

    public function updatePayment(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        $payment = Payment::findOrFail($id);

        // Валидация статуса
        $request->validate([
            'status' => 'required|in:Ожидание,Оплачено,Отменено',
        ]);

        // Обновление
        $payment->update([
            'status' => $request->status,
        ]);

        return response()->json(['success' => 'Статус платежа обновлён.']);
    }

    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['success' => 'Платёж удалён!']);
    }

    public function users()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Нет доступа!');
        }

        $users = User::all();
        Log::info('Пользователи загружены', ['count' => $users->count()]);

        return view('admin.users', compact('users'));
    }

    public function updateUser(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        Log::info('Запрос на обновление пользователя:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:tenant,landlord,admin',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            Log::info('Пользователь обновлён', $user->toArray());

            return response()->json(['success' => 'Пользователь обновлен']);
        } catch (\Exception $e) {
            Log::error('Ошибка обновления пользователя: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка обновления'], 500);
        }
    }

    public function deleteUser(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Нет доступа'], 403);
        }

        try {
            $user->delete();
            Log::info('Пользователь удалён', ['id' => $user->id]);

            return response()->json(['success' => 'Пользователь удален']);
        } catch (\Exception $e) {
            Log::error('Ошибка удаления пользователя: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка удаления'], 500);
        }
    }
    public function reports()
    {
        $reports = Report::with('listing')->paginate(15);
        return view('admin.reports', compact('reports'));
    }

    // Метод для закрытия репорта
    public function closeReport($reportId)
    {
        // Находим репорт и удаляем
        $report = Report::findOrFail($reportId);
        $report->delete(); // Удаляем репорт (или меняем статус на "закрыт")

        // Возвращаем сообщение в формате JSON
        return response()->json(['message' => 'Репорт закрыт.']);
    }

    // Метод для удаления репорта и объявления
    public function deleteReport($reportId)
    {
        // Находим репорт
        $report = Report::findOrFail($reportId);

        // Удаляем сам репорт
        $report->delete();

        // Находим и удаляем связанное объявление
        if ($report->listing) {
            $report->listing->delete();
        }

        // Возвращаем сообщение в формате JSON
        return response()->json(['message' => 'Репорт и объявление удалены.']);
    }

    public function reviews()
    {
        $reviews = Review::with(['user', 'listing'])->paginate(15); // Получаем отзывы с информацией о пользователе и объявлении
        return view('admin.reviews', compact('reviews'));
    }

    // Метод для удаления отзыва
    public function deleteReview($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        // Возвращаем админа на страницу с отзывами
        return response()->json(['success' => 'Отзыв удален.']);
    }

}
