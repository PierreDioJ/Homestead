document.addEventListener('DOMContentLoaded', function () {
    const rentButton = document.querySelector('#rent-button'); // Ссылка "Сдать жильё"

    if (!rentButton) return; // Если ссылки нет, выходим

    rentButton.addEventListener('click', function (event) {
        event.preventDefault(); // Предотвращаем переход по ссылке

        fetch('/listings/create', { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            method: 'GET' // Метод запроса, возможно, вам нужно использовать 'POST', если это форма
        })
        .then(response => {
            if (response.redirected) {
                // Если сервер возвращает редирект, показываем alert
                alert('Произошел редирект на страницу: ' + response.url);
                return;
            }

            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Ошибка при создании объявления.');
                });
            }

            window.location.href = '/listings/create';
        })
        .catch(error => {
            alert(error.message); // Показываем alert с ошибкой
        });
    });
});
