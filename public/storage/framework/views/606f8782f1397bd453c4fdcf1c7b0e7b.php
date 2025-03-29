<div class="container">
    <h1>👥 Управление пользователями</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><input type="text" name="name" value="<?php echo e($user->name); ?>" required></td>
                    <td><input type="email" name="email" value="<?php echo e($user->email); ?>" required></td>
                    <td>
                        <select name="role">
                            <option value="tenant" <?php echo e($user->role == 'tenant' ? 'selected' : ''); ?>>Арендатор</option>
                            <option value="landlord" <?php echo e($user->role == 'landlord' ? 'selected' : ''); ?>>Арендодатель</option>
                            <option value="admin" <?php echo e($user->role == 'admin' ? 'selected' : ''); ?>>Администратор</option>
                        </select>
                    </td>
                    <td>
                        <div class="manage">
                            <button type="button" class="btn-save" data-id="<?php echo e($user->id); ?>">Сохранить</button>
                            <button type="button" class="btn-delete" data-id="<?php echo e($user->id); ?>">Удалить</button>
                        </div>
                    </td>
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
                _method: 'PUT',
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

    .users-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .users-table th,
    .users-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .users-table th {
        background: #262626;
        color: white;
    }

    input,
    select {
        padding: 5px;
        font-size: 14px;
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
</style><?php /**PATH /var/www/html/resources/views/admin/users.blade.php ENDPATH**/ ?>