<div class="container">
    <h1>💳 Управление платежами</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="payments-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Объявление</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <form action="<?php echo e(route('admin.payments.update', $payment->id)); ?>" method="POST" class="payment-form">
                        <?php echo csrf_field(); ?>
                        <td><?php echo e($payment->id); ?></td>
                        <td><?php echo e($payment->user->name); ?></td>
                        <td><?php echo e($payment->listing->title); ?></td>
                        <td><?php echo e($payment->amount); ?> ₽</td>
                        <td>
                            <select name="status">
                                <option value="Ожидание" <?php echo e($payment->status == 'Ожидание' ? 'selected' : ''); ?>>Ожидание
                                </option>
                                <option value="Оплачено" <?php echo e($payment->status == 'Оплачено' ? 'selected' : ''); ?>>Оплачено
                                </option>
                                <option value="Отменено" <?php echo e($payment->status == 'Отменено' ? 'selected' : ''); ?>>Отменено
                                </option>
                            </select>
                        </td>
                        <td><?php echo e($payment->created_at->format('d.m.Y H:i')); ?></td>
                        <td>
                            <div class="manage">
                                <button type="button" class="btn-save" data-id="<?php echo e($payment->id); ?>">Сохранить</button>
                                <button type="button" class="btn-delete" data-id="<?php echo e($payment->id); ?>">Удалить</button>
                            </div>
                        </td>
                    </form>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        // Удаление платежа через AJAX
        $('.btn-delete').click(function () {
            let paymentId = $(this).data('id');
            let row = $(this).closest('tr');

            if (confirm('Вы уверены, что хотите удалить платёж?')) {
                $.ajax({
                    url: `/admin/payments/${paymentId}/delete`,
                    method: 'POST',
                    data: { _method: 'DELETE', _token: '<?php echo e(csrf_token()); ?>' },
                    success: function (response) {
                        alert(response.success);
                        row.remove();
                    },
                    error: function (xhr) {
                        alert('Ошибка удаления! ' + xhr.responseText);
                    }
                });
            }
        });

        // Обновление платежа через AJAX
        $('.btn-save').click(function () {
            let paymentId = $(this).data('id');
            let row = $(this).closest('tr');

            let formData = {
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>',
                status: row.find('select[name="status"]').val()
            };

            console.log("Отправка запроса на обновление платежа:", formData);

            $.ajax({
                url: `/admin/payments/${paymentId}/update`,
                method: 'POST',
                data: formData,
                success: function (response) {
                    alert(response.success);
                },
                error: function (xhr) {
                    alert('Ошибка обновления! ' + xhr.responseText);
                }
            });
        });
    });
</script>

<style>
    .manage {
        display: flex;
        gap: 10px;
    }

    .btn-save {
        width: 100%;
        color: #FFF;
        background-color: lightblue;
        font-family: "Roboto", sans-serif;
        font-size: 18px;
        border-radius: 8px;
        justify-content: center;
        align-items: center;
        padding: 5px;
        cursor: pointer;
        border: none;
    }

    .btn-delete {
        width: 100%;
        color: #FFF;
        background-color: lightcoral;
        font-family: "Roboto", sans-serif;
        font-size: 18px;
        border-radius: 8px;
        justify-content: center;
        align-items: center;
        padding: 5px;
        cursor: pointer;
        border: none;
    }

    .btn-save,
    .btn-delete {
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-save {
        background: #5bc0de;
        color: white;
        border-radius: 5px;
    }

    .btn-delete {
        background: #d9534f;
        color: white;
        border-radius: 5px;
    }

    .payments-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .payments-table th,
    .payments-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .payments-table th {
        background: #262626;
        color: white;
    }

    input,
    select {
        padding: 5px;
        font-size: 14px;
    }

</style><?php /**PATH /var/www/html/resources/views/admin/payments.blade.php ENDPATH**/ ?>