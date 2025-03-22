<div class="container">
    <h1>📊 Статистика и аналитика</h1>

    <!-- Карточки статистики -->
    <div class="stats-grid">
        <div class="stat-card">👥 Пользователи: <strong>{{ $totalUsers }}</strong></div>
        <div class="stat-card">📢 Объявления: <strong>{{ $totalListings }}</strong></div>
        <div class="stat-card">📆 Бронирования: <strong>{{ $totalBookings }}</strong></div>
        <div class="stat-card">💰 Общая сумма платежей: <strong>{{ number_format($totalRevenue, 2) }} ₽</strong></div>
    </div>

    <h2>📉 Графики</h2>
    <div id="bookingsChart" style="width: 100%; height: 400px;"></div>
    <div id="revenueChart" style="width: 100%; height: 400px;"></div>

    <h2>🔥 Топ-10 объявлений по бронированиям</h2>
    <table class="stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Бронирования</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topListings as $listing)
                <tr>
                    <td>{{ $listing->id }}</td>
                    <td>{{ $listing->title }}</td>
                    <td>{{ $listing->bookings_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>🏆 Топ-10 пользователей по платежам</h2>
    <table class="stats-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Сумма платежей</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topUsers as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ number_format($user->payments_sum_amount, 2) }} ₽</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Скрипт для подключения Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js" async></script>

<script type="text/javascript">
    console.log("Загружаем Google Charts...");

    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(function() {
        console.log("Google Charts загружен успешно!");
        drawCharts();
    });

    function drawCharts() {
        console.log("bookingsPerMonth:", @json($bookingsPerMonth));
        console.log("revenuePerMonth:", @json($revenuePerMonth));

        // График для бронирований
        var bookingsData = new google.visualization.DataTable();
        bookingsData.addColumn('string', 'Месяц');
        bookingsData.addColumn('number', 'Бронирования');
        bookingsData.addRows([
            @foreach ($bookingsPerMonth as $month => $count)
                ["{{ $month }}", {{ $count }}],
            @endforeach
        ]);

        // График для дохода
        var revenueData = new google.visualization.DataTable();
        revenueData.addColumn('string', 'Месяц');
        revenueData.addColumn('number', 'Доход');
        revenueData.addRows([
            @foreach ($revenuePerMonth as $month => $sum)
                ["{{ $month }}", {{ $sum }}],
            @endforeach
        ]);

        // Настройка графиков
        var bookingsChart = new google.visualization.LineChart(document.getElementById('bookingsChart'));
        var revenueChart = new google.visualization.LineChart(document.getElementById('revenueChart'));

        // Опции для графиков
        var optionsBookings = {
            title: 'Бронирования по месяцам',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { 
                title: 'Месяц',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            vAxis: { 
                title: 'Количество',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            series: {
                0: { color: '#1f77b4', lineWidth: 3 },
            },
            pointSize: 5, 
        };

        var optionsRevenue = {
            title: 'Доход по месяцам',
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: { 
                title: 'Месяц',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            vAxis: { 
                title: 'Доход (₽)',
                titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                textStyle: { fontSize: 12 }
            },
            series: {
                0: { color: '#ff7f0e', lineWidth: 3 },
            },
            pointSize: 5,
        };

        // Рисуем графики
        bookingsChart.draw(bookingsData, optionsBookings);
        revenueChart.draw(revenueData, optionsRevenue);
    }
</script>

<style>
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

    .stats-table th, .stats-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .stats-table th {
        background: #343a40;
        color: white;
    }

    #bookingsChart, #revenueChart {
        max-width: 100%;
        margin-top: 20px;
    }
</style>
