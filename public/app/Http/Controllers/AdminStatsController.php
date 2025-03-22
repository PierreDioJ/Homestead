<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminStatsController extends Controller
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

        // Получаем данные для статистики
        $totalUsers = User::count();
        $totalListings = Listing::count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::sum('amount');

        // Получаем данные для графиков
        $bookingsPerMonth = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');
        
        $revenuePerMonth = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as sum')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('sum', 'month');

        // Топ-10 объявлений по бронированиям
        $topListings = Listing::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(10)
            ->get();

        // Топ-10 пользователей по платежам
        $topUsers = User::selectRaw('users.*, COALESCE((SELECT SUM(payments.amount) FROM payments WHERE payments.user_id = users.id), 0) as payments_sum_amount')
            ->orderByDesc('payments_sum_amount')
            ->limit(10)
            ->get();

        return view('admin.stats', compact(
            'totalUsers', 'totalListings', 'totalBookings', 'totalRevenue',
            'bookingsPerMonth', 'revenuePerMonth', 'topListings', 'topUsers'
        ));
    }
}
