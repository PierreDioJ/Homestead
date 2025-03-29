<div class="container">
    <h1>📝 Управление отзывами</h1>

    <!-- Уведомление об успешном удалении -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Список отзывов -->
    <table class="table table-striped stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Объявление</th>
                <th>Оценка</th>
                <th>Комментарий</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->user->name }}</td>
                    <td>{{ $review->listing->title }}</td>
                    <td>{{ $review->rating }}</td>
                    <td>{{ $review->comment }}</td>
                    <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <!-- Кнопка для удаления отзыва -->
                        <form action="{{ route('admin.reviews.delete', $review->id) }}" method="POST"
                            class="delete-review-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm delete">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Пагинация -->
    <div class="pagination">
        {{ $reviews->links() }}
    </div>
</div>

<style>
    .pagination {
        margin-top: 20px;
        text-align: center;
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

    .delete {
        padding: 5px 10px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .delete {
        background: #d9534f;
        color: white;
        border-radius: 5px;
    }
</style>

<script>
    // Добавляем обработчик событий для всех форм удаления
    document.querySelectorAll('.delete-review-form').forEach(form => {
        form.addEventListener('submit', function (event) {
            // Предотвращаем отправку формы
            event.preventDefault();

            // Показываем подтверждение
            if (confirm('Вы уверены, что хотите удалить этот отзыв?')) {
                // Если пользователь подтвердил, отправляем форму с помощью fetch
                fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.success); // Покажем сообщение об успешном удалении
                            // Перезагружаем страницу, чтобы обновить список отзывов
                            location.reload();
                        } else {
                            alert('Ошибка при удалении отзыва');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                        alert('Произошла ошибка. Попробуйте еще раз.');
                    });
            }
        });
    });
</script>