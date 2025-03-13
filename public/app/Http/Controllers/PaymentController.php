<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use YooKassa\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Listing;
use Carbon\Carbon;
use App\Models\Message;

class PaymentController extends Controller
{
    public function createPayment(Request $request)
    {
        Log::info('Запрос на создание платежа', $request->all());

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Для оплаты необходимо авторизоваться.');
        }

        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'listing_id' => 'required|exists:listings,id',
        ]);

        // Получаем объявление
        $listing = Listing::findOrFail($request->listing_id);

        // Преобразуем строки в объекты Carbon для дат
        $checkInDate = Carbon::parse($request->check_in_date);
        $checkOutDate = Carbon::parse($request->check_out_date);

        // Рассчитываем количество дней между датами
        $daysCount = $checkInDate->diffInDays($checkOutDate);

        // Рассчитываем общую цену (цена за день умноженная на количество дней)
        $totalPrice = $listing->price * $daysCount;

        // Проверяем, нет ли уже бронирования на эти даты
        $existingBooking = Booking::where('listing_id', $request->listing_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                    ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date]);
            })->exists();

        if ($existingBooking) {
            return redirect()->back()->with('error', 'Выбранные даты уже забронированы.');
        }

        $shopId = config('services.yookassa.shop_id');
        $secretKey = config('services.yookassa.secret_key');

        if (!$shopId || !$secretKey) {
            Log::error('Ошибка: Не указаны shopId или secretKey для YooKassa.');
            return redirect()->back()->with('error', 'Ошибка настройки платежной системы.');
        }

        $client = new Client();
        $client->setAuth($shopId, $secretKey);

        try {
            // Создаем платеж в YooKassa
            $payment = $client->createPayment([
                'amount' => [
                    'value' => number_format($totalPrice, 2, '.', ''),
                    'currency' => 'RUB',
                ],
                'confirmation' => [
                    'type' => 'redirect',
                    'return_url' => route('payment.success'),
                ],
                'capture' => true,
                'description' => 'Оплата объявления #' . $request->listing_id,
                'metadata' => [
                    'user_id' => Auth::id(),
                    'listing_id' => $request->listing_id,
                    'check_in_date' => $request->check_in_date,
                    'check_out_date' => $request->check_out_date,
                    'total_price' => $totalPrice, // Добавляем расчетную цену
                ]
            ], uniqid('', true));

            // Сохраняем данные платежа в сессии
            Session::put('payment_data', [
                'payment_id' => $payment->getId(),
                'user_id' => Auth::id(),
                'listing_id' => $request->listing_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'amount' => $totalPrice, // Сохраняем расчетную сумму
            ]);

            Log::info('Платеж успешно создан', [
                'payment_id' => $payment->getId(),
                'confirmation_url' => $payment->getConfirmation()->getConfirmationUrl()
            ]);

            // Перенаправляем на страницу подтверждения платежа
            return redirect($payment->getConfirmation()->getConfirmationUrl());

        } catch (\Exception $e) {
            Log::error('Ошибка при создании платежа', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Ошибка при создании платежа. Попробуйте снова.');
        }
    }

    public function success(Request $request)
    {
        $paymentData = Session::get('payment_data');

        if (!$paymentData || !isset($paymentData['payment_id'])) {
            Log::error('Ошибка: Нет payment_id в сессии');
            return view('payment.failure')->with('error', 'Не удалось найти информацию о платеже.');
        }

        $shopId = config('services.yookassa.shop_id');
        $secretKey = config('services.yookassa.secret_key');
        $client = new \YooKassa\Client();
        $client->setAuth($shopId, $secretKey);

        try {
            $payment = $client->getPaymentInfo($paymentData['payment_id']);

            if ($payment->getStatus() === 'succeeded') {
                $listing = \App\Models\Listing::findOrFail($paymentData['listing_id']);

                Log::info('Платеж успешно завершен, создаем бронирование', $paymentData);

                // ✅ Создаём бронирование в БД
                $booking = \App\Models\Booking::create([
                    'user_id' => $paymentData['user_id'],
                    'listing_id' => $paymentData['listing_id'],
                    'check_in_date' => $paymentData['check_in_date'],
                    'check_out_date' => $paymentData['check_out_date'],
                    'status' => 'Оплачено',
                    'landlord_id' => $listing->user_id,
                    'expires_at' => now()->addDays(1),
                    'payment_receipt' => $paymentData['payment_id']
                ]);

                // ✅ Записываем платёж в базу
                \App\Models\Payment::create([
                    'user_id' => $paymentData['user_id'],
                    'listing_id' => $paymentData['listing_id'],
                    'amount' => $paymentData['amount'],
                    'status' => 'Оплачено',
                ]);

                Log::info('✅ Бронирование и платеж успешно добавлены в базу данных', ['booking_id' => $booking->id]);

                // ✅ Очищаем сессию
                Session::forget('payment_data');

                // ✅ Передаём `booking` в представление
                return view('payment.success', compact('booking', 'listing'));
            } else {
                Log::error('❌ Оплата не прошла', ['status' => $payment->getStatus()]);
                return view('payment.failure')->with('error', 'Оплата не прошла. Попробуйте снова.');
            }
        } catch (\Exception $e) {
            Log::error('❌ Ошибка при проверке платежа', ['message' => $e->getMessage()]);
            return view('payment.failure')->with('error', 'Ошибка при проверке статуса платежа.');
        }
    }


}
