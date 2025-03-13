<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <title>Homestead | Успешная оплата</title>
    <link rel="stylesheet" href="/css/styles-payment.css">
    <link rel="stylesheet" href="/css/styles-general.css">
</head>

<body>
   <x-header />
    <div class="wrapper">
        <main class="main">
            <section class="main-page">
                <div class="container">
                    <div class="box-payment">
                        <div class="container-payment">
                            <div class="payment-up">
                                <h1 class="payment-title">Успех!</h1>
                            </div>
                            <div class="payment-middle">
                                <div class="payment-welcome-text">
                                    <p class="payment-welcome">Вы забронировали объявление</p>
                                    <form action="{{ route('chat.show', ['id' => $booking->id]) }}" method="GET">
                                        <button type="submit" class="button-cont">Перейти к объявлению</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <x-footer />
    </div>
</body>

</html>