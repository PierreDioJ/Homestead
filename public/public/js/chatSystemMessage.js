document.addEventListener('DOMContentLoaded', function () {
        let chatForm = document.getElementById('chat-form');
        let chatMessages = document.getElementById('chat-messages');

        function appendMessage(text, isSystem = false) {
            let newMessage = document.createElement('div');
            newMessage.classList.add('message', isSystem ? 'system' : 'sent');
            newMessage.innerHTML = `<p>${text}</p><small>Системное сообщение</small>`;
            chatMessages.appendChild(newMessage);
        }

        // Передаем данные из Blade в JavaScript
        let userName = "{{ $user->name }}";
        let listingTitle = "{{ $listing->title }}";
        let listingId = "{{ $listing->id }}";
        let checkInDate = "{{ $check_in_date }}";
        let checkOutDate = "{{ $check_out_date }}";
        let totalPrice = "{{ number_format($total_price, 0, '', ' ') }}";

        // Формируем системное сообщение
        let systemMessage = `Пользователь ${userName} забронировал жильё "${listingTitle}" (ID: ${listingId}) с ${checkInDate} по ${checkOutDate} на сумму ${totalPrice} ₽. Ожидайте ответа арендодателя.`;

        // Если есть системное сообщение в сессии (например, после оплаты)
        if (systemMessage) {
            appendMessage(systemMessage, true);
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
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        appendMessage(data.message.message);
                        messageInput.value = '';
                    }
                })
                .catch(error => console.error('Ошибка при отправке сообщения:', error));
            });
        }
    });

