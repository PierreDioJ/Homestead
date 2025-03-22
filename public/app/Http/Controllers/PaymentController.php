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

        $listing = Listing::findOrFail($request->listing_id);
        $checkInDate = Carbon::parse($request->check_in_date);
        $checkOutDate = Carbon::parse($request->check_out_date);
        $daysCount = max($checkInDate->diffInDays($checkOutDate), 1);
        $totalPrice = $listing->price * $daysCount;

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
                    'total_price' => $totalPrice,
                    'guests' => $request->guests,
                ]
            ], uniqid('', true));

            Session::put('payment_data', [
                'payment_id' => $payment->getId(),
                'user_id' => Auth::id(),
                'listing_id' => $request->listing_id,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'amount' => $totalPrice,
                'guests' => $request->guests,
            ]);

            Log::info('Платеж успешно создан', [
                'payment_id' => $payment->getId(),
                'confirmation_url' => $payment->getConfirmation()->getConfirmationUrl()
            ]);

            return redirect($payment->getConfirmation()->getConfirmationUrl());

        } catch (\Exception $e) {
            Log::error('Ошибка при создании платежа', ['message' => $e->getMessage()]);
            return redirect()->route('payment.failure')->with('error', 'Ошибка при создании платежа. Попробуйте снова.');
        }
    }

    public function success(Request $request)
    {
        $paymentData = Session::get('payment_data');

        if (!$paymentData || !isset($paymentData['payment_id'])) {
            Log::error('Ошибка: Нет payment_id в сессии');
            return redirect()->route('payment.failure')->with('error', 'Не удалось найти информацию о платеже.');
        }

        $shopId = config('services.yookassa.shop_id');
        $secretKey = config('services.yookassa.secret_key');
        $client = new Client();
        $client->setAuth($shopId, $secretKey);

        try {
            $payment = $client->getPaymentInfo($paymentData['payment_id']);

            if ($payment->getStatus() === 'succeeded') {
                $listing = Listing::findOrFail($paymentData['listing_id']);
                $user = Auth::user();
                $landlord = $listing->user_id;

                $guests = $paymentData['guests'] ?? 1;

                $booking = Booking::create([
                    'user_id' => $paymentData['user_id'],
                    'listing_id' => $paymentData['listing_id'],
                    'check_in_date' => $paymentData['check_in_date'],
                    'check_out_date' => $paymentData['check_out_date'],
                    'status' => 'Оплачено',
                    'landlord_id' => $landlord,
                    'expires_at' => now()->addDays(1),
                    'payment_receipt' => $paymentData['payment_id'],
                    'guests' => $guests,
                ]);

                Payment::create([
                    'user_id' => $paymentData['user_id'],
                    'listing_id' => $paymentData['listing_id'],
                    'amount' => $paymentData['amount'],
                    'status' => 'Оплачено',
                ]);

                // Сообщение для арендодателя
                Message::create([
                    'sender_id' => 26,
                    'receiver_id' => $landlord,
                    'listing_id' => $listing->id,
                    'booking_id' => $booking->id,
                    'message' => "Пользователь {$user->name} забронировал жильё \"{$listing->title}\" (ID: {$listing->id}) с {$paymentData['check_in_date']} по {$paymentData['check_out_date']} на {$guests} гостей. Сумма: {$paymentData['amount']} ₽. Начните диалог!",
                    'is_read' => 0,
                ]);

                // Сообщение для арендатора
                Message::create([
                    'sender_id' => 26,
                    'receiver_id' => $user->id,
                    'listing_id' => $listing->id,
                    'booking_id' => $booking->id,
                    'message' => "Вы забронировали жильё \"{$listing->title}\" (ID: {$listing->id}) с {$paymentData['check_in_date']} по {$paymentData['check_out_date']} на {$guests} гостей. Сумма: {$paymentData['amount']} ₽. Ожидайте ответа арендодателя!",
                    'is_read' => 0,
                ]);

                Log::info('✅ Системные сообщения успешно отправлены.');

                Session::forget('payment_data');

                session()->put('system_message', "Пользователь {$user->name} забронировал жильё \"{$listing->title}\" (ID: {$listing->id}) с {$paymentData['check_in_date']} по {$paymentData['check_out_date']} на {$guests} гостей. Сумма: {$paymentData['amount']} ₽. Ожидайте ответа арендодателя.");

                return redirect()->route('chat.show', ['id' => $booking->id])->with('success', 'Оплата успешно завершена.');
            } else {
                return redirect()->route('payment.failure')->with('error', 'Оплата не прошла. Попробуйте снова.');
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при проверке платежа', ['message' => $e->getMessage()]);
            return redirect()->route('payment.failure')->with('error', 'Ошибка при проверке статуса платежа.');
        }
    }

    public function failure()
    {
        return view('payment.failure')->with('error', session('error', 'Ошибка оплаты.'));
    }
}
