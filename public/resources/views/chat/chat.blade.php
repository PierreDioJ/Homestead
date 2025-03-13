<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Чаты</title>
    <link rel="stylesheet" href="/css/styles-chat.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <x-header />
    <div class="wrapper">
    <main class="main">
        <section class="main-page">
            <div class="container chat-container">
                <div class="chat-list">
                    <h3>Мои чаты</h3>
                    @foreach($bookings as $booking)
                        <a href="{{ route('chat.show', ['id' => $booking->id]) }}"
                            class="{{ isset($activeChat) && $activeChat->id == $booking->id ? 'active' : '' }}">
                            {{ $booking->listing->title ?? 'Без названия' }}
                        </a>
                    @endforeach
                </div>
                @if(isset($activeChat))
                    <div class="chat-box">
                        <h2>Чат с арендодателем: {{ $landlord ? $landlord->name : 'Неизвестный арендодатель' }}</h2>
                        <div class="chat-messages" id="chat-messages">
                        @foreach($messages as $message)
    <div class="message {{ $message->sender_id == Auth::id() ? 'sent' : 'received' }}">
        @if($message->is_system) 
            <p class="system-message"><strong>{{ $message->message }}</strong></p>
        @else
            <p>{{ $message->message }}</p>
        @endif
        <small>{{ $message->created_at->format('d.m.Y H:i') }}</small>
    </div>
@endforeach

                        </div>
                        <div class="chat-input">
                            <form id="chat-form" action="{{ route('chat.send', ['id' => $activeChat->id]) }}" method="POST">
                                @csrf
                                <textarea id="message-input" name="message" placeholder="Введите сообщение..."
                                    required></textarea>
                                <button type="submit" class="btn btn-primary">Отправить</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="chat-box">
                        <h2>Выберите чат из списка</h2>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <x-footer />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let chatForm = document.getElementById('chat-form');

            if (chatForm) {
                chatForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    let messageInput = document.getElementById('message-input');
                    let message = messageInput.value.trim();
                    if (message === '') return;
                    fetch(chatForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message: message })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                let chatMessages = document.getElementById('chat-messages');
                                let newMessage = document.createElement('div');
                                newMessage.classList.add('message', 'sent');
                                newMessage.innerHTML = `<p>${data.message.message}</p><small>Только что</small>`;
                                chatMessages.appendChild(newMessage);
                                messageInput.value = '';
                            }
                        })
                        .catch(error => console.error('Ошибка при отправке сообщения:', error));
                });
            }
        });
    </script>
</body>

</html>