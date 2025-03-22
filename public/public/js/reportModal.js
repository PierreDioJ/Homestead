// Получаем элементы
const reportBtn = document.querySelector('.report-btn');
const modal = document.getElementById('reportModal');
const closeModal = document.querySelector('.close');
const reportForm = document.getElementById('reportForm');

// Открытие модального окна
reportBtn.addEventListener('click', () => {
    modal.style.display = 'flex';
});

// Закрытие модального окна
closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

// Обработка отправки формы
reportForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(reportForm);
    const reason = formData.get('reason');
    const details = formData.get('details');

    // Проверяем, передан ли ID объявления
    const listingId = reportForm.dataset.listingId;
    if (!listingId) {
        alert('Ошибка: ID объявления отсутствует.');
        return;
    }

    // Отправляем данные на сервер через Fetch API
    fetch('/reports', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            listing_id: listingId,
            reason: reason,
            details: details,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Произошла ошибка при отправке жалобы.');
            }
            return response.json();
        })
        .then((data) => {
            alert(data.message || 'Спасибо, ваша жалоба была отправлена!');
            modal.style.display = 'none';
            reportForm.reset(); // Сброс формы после успешной отправки
        })
        .catch((error) => {
            console.error('Ошибка:', error);
            alert('Не удалось отправить жалобу. Попробуйте ещё раз.');
        });
});
