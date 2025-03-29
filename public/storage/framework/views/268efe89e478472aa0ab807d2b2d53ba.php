<div class="container">
    <h1>📊 Репорты</h1>

    <!-- Уведомления об успехе -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Карточки статистики -->
    <div class="stats-grid">
        <div class="stat-card">📝 Общее количество репортов: <strong><?php echo e($reports->count()); ?></strong></div>
    </div>

    <h2>📈 Список отчетов</h2>
    <table class="stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Объявление</th>
                <th>Причина</th>
                <th>Детали</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($report->id); ?></td>
                    <td><?php echo e($report->listing ? $report->listing->title : 'Не указано'); ?></td>
                    <td><?php echo e($report->reason); ?></td>
                    <td><?php echo e($report->details); ?></td>
                    <td><?php echo e($report->created_at); ?></td>
                    <td>
                        <!-- Кнопка "Перейти" для просмотра объявления -->
                        <a href="<?php echo e(route('listings.show', $report->listing_id)); ?>" class="btn btn-primary btn-sm direct"
                            target="_blank">Перейти</a>
                        <div class="manage">
                            <!-- Кнопка "Закрыть" для удаления репорта -->
                            <button class="btn btn-secondary btn-sm close"
                                onclick="closeReport(<?php echo e($report->id); ?>)">Закрыть</button>

                            <!-- Кнопка "Удалить" для удаления объявления и репорта -->
                            <button class="btn btn-danger btn-sm delete"
                                onclick="deleteReport(<?php echo e($report->id); ?>)">Удалить</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Пагинация -->
    <div class="pagination">
        <?php echo e($reports->links()); ?>

    </div>
</div>

<style>
    .direct {
        display: flex;
        text-decoration: none;
        color: #FFF;
        background-color: aquamarine;
        font-family: "Roboto", sans-serif;
        font-size: 20px;
        border-radius: 8px;
        justify-content: center;
        align-items: center;
        padding: 5px;
        margin-bottom: 10px;
    }

    .manage {
        display: flex;
        gap: 10px;
    }

    .close {
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

    .delete {
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

    .close,
    .delete {
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .close {
        background: #5bc0de;
        color: white;
        border-radius: 5px;
    }

    .delete {
        background: #d9534f;
        color: white;
        border-radius: 5px;
    }

    .stats-grid {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
    }

    .stats-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .stats-table th,
    .stats-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .stats-table th {
        background: #343a40;
        color: white;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }
</style>

<script>
    // Добавление CSRF-токена в заголовок AJAX-запросов
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Закрыть репорт
    function closeReport(reportId) {
        if (confirm('Вы уверены, что хотите закрыть этот репорт?')) {
            // Выполнить AJAX запрос для закрытия отчета
            $.ajax({
                url: '/admin/reports/' + reportId + '/close',
                type: 'DELETE',
                success: function (response) {
                    alert(response.message);
                    location.reload();  // Перезагрузить страницу для обновления
                },
                error: function (xhr, status, error) {
                    alert('Произошла ошибка при закрытии репорта!');
                }
            });
        }
    }

    // Удалить репорт и объявление
    function deleteReport(reportId) {
        if (confirm('Вы уверены, что хотите удалить этот репорт и связанное с ним объявление?')) {
            // Выполнить AJAX запрос для удаления отчета и объявления
            $.ajax({
                url: '/admin/reports/' + reportId + '/delete',
                type: 'DELETE',
                success: function (response) {
                    alert(response.message);
                    location.reload();  // Перезагрузить страницу для обновления
                },
                error: function (xhr, status, error) {
                    alert('Произошла ошибка при удалении репорта!');
                }
            });
        }
    }
</script><?php /**PATH /var/www/html/resources/views/admin/reports.blade.php ENDPATH**/ ?>