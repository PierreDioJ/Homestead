<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Homestead | Чаты</title>
    <link rel="stylesheet" href="/css/styles-chat.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
    <?php if (isset($component)) { $__componentOriginal2a2e454b2e62574a80c8110e5f128b60 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a2e454b2e62574a80c8110e5f128b60 = $attributes; } ?>
<?php $component = App\View\Components\Header::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Header::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a2e454b2e62574a80c8110e5f128b60)): ?>
<?php $attributes = $__attributesOriginal2a2e454b2e62574a80c8110e5f128b60; ?>
<?php unset($__attributesOriginal2a2e454b2e62574a80c8110e5f128b60); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a2e454b2e62574a80c8110e5f128b60)): ?>
<?php $component = $__componentOriginal2a2e454b2e62574a80c8110e5f128b60; ?>
<?php unset($__componentOriginal2a2e454b2e62574a80c8110e5f128b60); ?>
<?php endif; ?>
    <div class="wrapper">
        <main class="main">
            <section class="main-page">
                <div class="container chat-container">
                    <div class="chat-list">
                        <h3>Мои чаты</h3>
                        <?php $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('chat.show', ['id' => $booking->id])); ?>"
                                class="<?php echo e(isset($activeChat) && $activeChat->id == $booking->id ? 'active' : ''); ?>">
                                <?php echo e($booking->listing->title ?? 'Без названия'); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php if(isset($activeChat)): ?>
                        <div class="chat-box">
                            <h2>Чат с <?php echo e(Auth::id() === $activeChat->landlord_id ? 'арендатором' : 'арендодателем'); ?>:
                                <?php echo e($companion ? $companion->name : 'Неизвестный пользователь'); ?>

                            </h2>
                            <div class="chat-messages" id="chat-messages">
                                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($message->receiver_id == Auth::id() || $message->sender_id == Auth::id()): ?>
                                        <div
                                            class="message <?php echo e($message->is_system ? 'system-message' : ($message->sender_id == Auth::id() ? 'sent' : 'received')); ?>">
                                            <p><?php echo e($message->message); ?></p>
                                            <small><?php echo e($message->created_at->format('d.m.Y H:i')); ?></small>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="chat-input">
                                <form id="chat-form" action="<?php echo e(route('chat.send', ['id' => $activeChat->id])); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <textarea id="message-input" name="message" placeholder="Введите сообщение..."
                                        required></textarea>
                                    <button type="submit" class="btn btn-primary">Отправить</button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="chat-box">
                            <h2>Выберите чат из списка</h2>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <?php if (isset($component)) { $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa = $attributes; } ?>
<?php $component = App\View\Components\Footer::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Footer::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $attributes = $__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__attributesOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa)): ?>
<?php $component = $__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa; ?>
<?php unset($__componentOriginal99051027c5120c83a2f9a5ae7c4c3cfa); ?>
<?php endif; ?>
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

</html><?php /**PATH /var/www/html/resources/views/chat/chat.blade.php ENDPATH**/ ?>