<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Показать все бронирования.
     */
    public function index()
    {
        $bookings = Booking::with('user', 'listing')->get();
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Показать форму для создания бронирования.
     */
    public function create()
    {
        $listings = Listing::all();
        return view('bookings.create', compact('listings'));
    }

    /**
     * Сохранить новое бронирование.
     */
    public function store(Request $request)
    {
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $listing = Listing::findOrFail($request->listing_id);

        // Создание бронирования со статусом "pending" (ожидание)
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15), // Истекает через 15 минут
        ]);

        return redirect()->route('bookings.index')->with('success', 'Бронирование создано! Оплатите его в течение 15 минут.');
    }

    /**
     * Подтверждение оплаты бронирования.
     */
    public function confirmPayment($id)
    {
        $booking = Booking::findOrFail($id);
        

        // Проверяем, не истекло ли бронирование
        if ($booking->expires_at < Carbon::now()) {
            return redirect()->route('bookings.index')->with('error', 'Время на оплату истекло, бронирование отменено.');
        }

        // Подтверждаем оплату
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('bookings.index')->with('success', 'Оплата подтверждена! Бронирование успешно.');
    }

    /**
     * Загрузка чека об оплате.
     */
    public function uploadReceipt(Request $request, $id)
    {
        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $booking = Booking::findOrFail($id);

        // Удаляем старый чек, если он есть
        if ($booking->receipt) {
            Storage::disk('public')->delete($booking->receipt);
        }

        // Сохраняем новый чек
        $receiptPath = $request->file('receipt')->store('receipts', 'public');

        // Обновляем статус на "confirmed" и сохраняем чек
        $booking->update([
            'status' => 'confirmed',
            'receipt' => $receiptPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Чек успешно загружен! Оплата подтверждена.']);
    }

    /**
     * Удалить бронирование.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('bookings.index');
    }
}
