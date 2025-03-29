<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h2>Чат с владельцем объявления</h2>
        <div class="chat-box">
            <div class="chat-header">
                <h3>Вы общаетесь с <?php echo e($landlord->name); ?></h3>
            </div>
            <div class="chat-messages">
                <p>Здесь будет история сообщений...</p>
            </div>
            <div class="chat-input">
                <form action="#" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="message" placeholder="Введите сообщение..." required>
                    <button type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let chatForm = document.getElementById('chat-form');

            chatForm.addEventListener('submit', function (e) {
                e.preventDefault();

                let messageInput = document.getElementById('message-input');
                let message = messageInput.value.trim();

                if (message === '') return;

                fetch('<?php echo e(route("chat.send")); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        listing_id: '<?php echo e($listing->id); ?>',
                        receiver_id: '<?php echo e($landlord->id); ?>',
                        message: message
                    })
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let chatMessages = document.getElementById('chat-messages');
                            let newMessage = document.createElement('div');
                            newMessage.classList.add('message', 'sent');
                            newMessage.innerHTML = `<p>${message}</p><small>Только что</small>`;
                            chatMessages.appendChild(newMessage);
                            messageInput.value = '';
                        }
                    });
            });
        });
    </script>

</body>

</html><?php /**PATH /var/www/html/resources/views/chat/show.blade.php ENDPATH**/ ?>