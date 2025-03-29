<div class="container">
    <h1>💳 Управление платежами</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
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
            @foreach ($payments as $payment)
                <tr>
                    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST" class="payment-form">
                        @csrf
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->user->name }}</td>
                        <td>{{ $payment->listing->title }}</td>
                        <td>{{ $payment->amount }} ₽</td>
                        <td>
                            <select name="status">
                                <option value="Ожидание" {{ $payment->status == 'Ожидание' ? 'selected' : '' }}>Ожидание
                                </option>
                                <option value="Оплачено" {{ $payment->status == 'Оплачено' ? 'selected' : '' }}>Оплачено
                                </option>
                                <option value="Отменено" {{ $payment->status == 'Отменено' ? 'selected' : '' }}>Отменено
                                </option>
                            </select>
                        </td>
                        <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="manage">
                                <button type="button" class="btn-save" data-id="{{ $payment->id }}">Сохранить</button>
                                <button type="button" class="btn-delete" data-id="{{ $payment->id }}">Удалить</button>
                            </div>
                        </td>
                    </form>
                </tr>
            @endforeach
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

        // Обновление платежа через AJAX
        $('.btn-save').click(function () {
            let paymentId = $(this).data('id');
            let row = $(this).closest('tr');

            let formData = {
                _method: 'POST',
                _token: '{{ csrf_token() }}',
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

</style>