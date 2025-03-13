<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // 📥 Отображение списка всех чатов пользователя
    public function userChats()
    {
        $userId = Auth::id();

        // Получаем все бронирования, где пользователь участвовал как арендатор или арендодатель
        $bookings = Booking::where('user_id', $userId)
            ->orWhere('landlord_id', $userId)
            ->with('listing')
            ->get();

        return view('chat.chat', compact('bookings'));
    }

    // 💬 Отображение чата для конкретного бронирования
    public function index($id = null)
    {
        $userId = Auth::id();

        // Получаем все чаты, где пользователь является арендатором или арендодателем
        $bookings = Booking::where('user_id', $userId)
            ->orWhere('landlord_id', $userId)
            ->with('listing')
            ->get();

        // Активный чат и сообщения по умолчанию
        $activeChat = null;
        $messages = collect();
        $landlord = null;

        // Если выбран конкретный чат
        if ($id) {
            $activeChat = Booking::with('listing', 'user')->findOrFail($id);

            // Проверяем права доступа
            if ($userId !== $activeChat->user_id && $userId !== $activeChat->landlord_id) {
                abort(403, 'У вас нет доступа к этому чату.');
            }

            $messages = Message::where('booking_id', $id)->orderBy('created_at', 'asc')->get();
            $landlord = $activeChat->landlord_id ? User::find($activeChat->landlord_id) : null;
        }

        // Передача всех необходимых данных в шаблон
        return view('chat.chat', compact('bookings', 'activeChat', 'messages', 'landlord'));
    }



    // 📤 Отправка сообщения
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $booking = Booking::findOrFail($id);

        // Определяем получателя: арендатор или арендодатель
        $receiverId = (Auth::id() === $booking->user_id) ? $booking->landlord_id : $booking->user_id;

        $message = Message::create([
            'booking_id' => $booking->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiverId,
            'listing_id' => $booking->listing_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        Log::info('Сохраненное сообщение:', $message->toArray());

        return response()->json(['success' => true, 'message' => $message]);
    }
}
