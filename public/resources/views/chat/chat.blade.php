<!DOCTYPE html>
<html lang="ru">

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
                            <h2>Чат с {{ Auth::id() === $activeChat->landlord_id ? 'арендатором' : 'арендодателем' }}:
                                {{ $companion ? $companion->name : 'Неизвестный пользователь' }}
                            </h2>
                            <div class="chat-messages" id="chat-messages">
                                @foreach($messages as $message)
                                    @if($message->receiver_id == Auth::id() || $message->sender_id == Auth::id())
                                        <div
                                            class="message {{ $message->is_system ? 'system-message' : ($message->sender_id == Auth::id() ? 'sent' : 'received') }}">
                                            <p>{{ $message->message }}</p>
                                            <small>{{ $message->created_at->format('d.m.Y H:i') }}</small>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="chat-input">
                                <form id="chat-form" action="{{ route('chat.send', ['id' => $activeChat->id]) }}"
                                    method="POST">
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
            let chatMessages = document.getElementById('chat-messages');
            let chatForm = document.getElementById('chat-form');

            function appendMessage(text, type = 'sent') {
                let newMessage = document.createElement('div');
                newMessage.classList.add('message', type);
                newMessage.innerHTML = `<p>${text}</p><small>${new Date().toLocaleString('ru-RU')}</small>`;
                chatMessages.appendChild(newMessage);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }

            if (chatForm) {
                chatForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    let messageInput = document.getElementById('message-input');
                    let message = messageInput.value.trim();
                    if (message === '') return;

                    fetch(chatForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ message: message })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                appendMessage(data.message.message, 'sent');
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