<div class="container">
    <h1>📢 Управление объявлениями</h1>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <table class="listings-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $listings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $listing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($listing->id); ?></td>
                    <td><input type="text" name="title" value="<?php echo e($listing->title); ?>" required></td>
                    <td><textarea name="description" required><?php echo e($listing->description); ?></textarea></td>
                    <td><input type="number" name="price" value="<?php echo e($listing->price); ?>" required></td>
                    <td>
                        <div class="manage">
                            <button type="button" class="btn-save" data-id="<?php echo e($listing->id); ?>">Сохранить</button>
                            <button type="button" class="btn-delete" data-id="<?php echo e($listing->id); ?>">Удалить</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        // Удаление объявления через AJAX
        $('.btn-delete').click(function () {
            let listingId = $(this).data('id');
            let row = $(this).closest('tr');

            if (confirm('Вы уверены, что хотите удалить объявление?')) {
                $.ajax({
                    url: `/admin/listings/${listingId}/delete`,
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

        // Обновление объявления через AJAX
        $('.btn-save').click(function () {
            let listingId = $(this).data('id');
            let row = $(this).closest('tr');

            let formData = {
                _method: 'POST',
                _token: '<?php echo e(csrf_token()); ?>',
                title: row.find('input[name="title"]').val(),
                description: row.find('textarea[name="description"]').val(),
                price: row.find('input[name="price"]').val()
            };

            console.log("Отправка запроса на обновление:", formData);

            $.ajax({
                url: `/admin/listings/${listingId}/update`,
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

    .listings-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .listings-table th,
    .listings-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .listings-table th {
        background: #262626;
        color: white;
    }

    input,
    textarea {
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
</style><?php /**PATH /var/www/html/resources/views/admin/listings.blade.php ENDPATH**/ ?>