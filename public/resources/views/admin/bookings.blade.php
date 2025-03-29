<div class="container">
    <h1>📅 Управление бронированиями</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="bookings-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Объявление</th>
                <th>Дата заезда</th>
                <th>Дата выезда</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->listing->title }}</td>
                    <td><input type="date" name="check_in_date" value="{{ $booking->check_in_date }}" required></td>
                    <td><input type="date" name="check_out_date" value="{{ $booking->check_out_date }}" required></td>
                    <td>
                        <select name="status">
                            <option value="Ожидание" {{ $booking->status == 'Ожидание' ? 'selected' : '' }}>Ожидание</option>
                            <option value="Оплачено" {{ $booking->status == 'Оплачено' ? 'selected' : '' }}>Оплачено</option>
                            <option value="Отменено" {{ $booking->status == 'Отменено' ? 'selected' : '' }}>Отменено</option>
                        </select>
                    </td>
                    <td>
                        <div class="manage">
                            <button type="button" class="btn-save" data-id="{{ $booking->id }}">Сохранить</button>
                            <button type="button" class="btn-delete" data-id="{{ $booking->id }}">Удалить</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
        // Удаление бронирования через AJAX
        $('.btn-delete').click(function () {
            let bookingId = $(this).data('id');
            let row = $(this).closest('tr');

            if (confirm('Вы уверены, что хотите удалить бронирование?')) {
                $.ajax({
                    url: `/admin/bookings/${bookingId}/delete`,
                    method: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
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

        // Обновление бронирования через AJAX
        $('.btn-save').click(function () {
            let bookingId = $(this).data('id');
            let row = $(this).closest('tr');

            let formData = {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
                check_in_date: row.find('input[name="check_in_date"]').val(),
                check_out_date: row.find('input[name="check_out_date"]').val(),
                status: row.find('select[name="status"]').val()
            };

            $.ajax({
                url: `/admin/bookings/${bookingId}/update`,
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

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .bookings-table th,
    .bookings-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .bookings-table th {
        background: #262626;
        color: white;
    }

    input,
    select {
        padding: 5px;
        font-size: 14px;
    }
</style>