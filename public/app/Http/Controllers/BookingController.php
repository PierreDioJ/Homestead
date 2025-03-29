<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Подключаем фасад для логирования
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
        // Логируем все входящие данные запроса
        Log::info('Получены данные для бронирования:', [
            'listing_id' => $request->input('listing_id'),
            'check_in_date' => $request->input('check_in_date'),
            'check_out_date' => $request->input('check_out_date'),
            'guests' => $request->input('guests') // Логируем количество гостей
        ]);

        // Валидация данных
        $request->validate([
            'listing_id' => 'required|exists:listings,id',
            'check_in_date' => 'required|date|after_or_equal:today', // Проверка, что дата заезда не в прошлом
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1|max:' . $request->input('guests'), // Логируем максимальное количество гостей
        ]);

        $listing = Listing::findOrFail($request->input('listing_id'));

        // Логируем информацию о найденном объекте
        Log::info('Информация о найденном жилье:', [
            'listing_title' => $listing->title,
            'max_guests' => $listing->guests
        ]);

        // Проверка на пересечение бронирований
        $existingBooking = Booking::where('listing_id', $listing->id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->input('check_in_date'), $request->input('check_out_date')])
                    ->orWhereBetween('check_out_date', [$request->input('check_in_date'), $request->input('check_out_date')]);
            })
            ->exists();

        if ($existingBooking) {
            return redirect()->route('bookings.create')->with('error', 'На выбранные даты уже есть бронирование.');
        }

        // Логируем информацию перед созданием бронирования
        Log::info('Данные для создания бронирования:', [
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'check_in_date' => $request->input('check_in_date'),
            'check_out_date' => $request->input('check_out_date'),
            'guests' => $request->input('guests') // Логируем количество гостей перед сохранением
        ]);

        // Создаем бронирование с количеством гостей
        // Ваш код для сохранения бронирования
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'listing_id' => $listing->id,
            'check_in_date' => $request->input('check_in_date'),
            'check_out_date' => $request->input('check_out_date'),
            'guests' => $request->input('guests'),
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        

        // Логируем успешное создание бронирования
        Log::info('Бронирование успешно создано:', [
            'booking_id' => $booking->id,
            'guests' => $booking->guests // Логируем количество гостей после создания бронирования
        ]);

        // Возвращаем данные о бронировании
        return redirect()->route('bookings.index')->with('success', 'Бронирование создано! Оплатите его в течение 15 минут.')->with('booking', $booking);
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
