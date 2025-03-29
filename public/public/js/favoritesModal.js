document.addEventListener('DOMContentLoaded', function () {
    const favoriteModal = document.getElementById('favoriteModal');
    const closeModal = document.querySelector('.btn-close');
    const favoritesGrid = document.querySelector('.favorites-grid');
    const favoriteBtn = document.querySelector('.btn-favorite');

    if (!favoriteBtn) return;

    // 🔥 Открытие модального окна и загрузка избранных объявлений
    favoriteBtn.addEventListener('click', function () {
        fetch('/favorites')
            .then(response => response.json())
            .then(data => {
                favoritesGrid.innerHTML = ''; // Очищаем перед добавлением

                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }

                if (data.length === 0) {
                    favoritesGrid.innerHTML = '<p>У вас пока нет избранных объявлений.</p>';
                    return;
                }

                // 🏠 Отображение избранных объявлений
                data.forEach(listing => {
                    const item = document.createElement('div');
                    item.classList.add('favorite-item');
                    item.innerHTML = `
                        <img src="${listing.photo ? '/storage/' + listing.photo : '/img/default.jpg'}" alt="Фото" class="favorite-img">
                        <div class="favorite-details">
                            <h3>${listing.title}</h3>
                            <p>${listing.price} ₽/ночь</p>
                        </div>
                        <button class="remove-favorite" data-id="${listing.id}">❌</button>
                    `;
                    favoritesGrid.appendChild(item);
                });

                // ✅ Обработчик удаления
                document.querySelectorAll('.remove-favorite').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const listingId = this.getAttribute('data-id');

                        fetch(`/favorite/${listingId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(() => {
                            this.parentElement.remove();
                            if (favoritesGrid.children.length === 0) {
                                favoritesGrid.innerHTML = '<p>У вас пока нет избранных объявлений.</p>';
                            }
                        })
                        .catch(error => console.error('Ошибка удаления:', error));
                    });
                });
            })
            .catch(error => {
                console.error('Ошибка загрузки избранного:', error);
                favoritesGrid.innerHTML = '<p>Ошибка загрузки избранных.</p>';
            });

        favoriteModal.style.display = 'flex';
    });

    // 🔥 Закрытие модального окна
    closeModal.addEventListener('click', () => {
        favoriteModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === favoriteModal) {
            favoriteModal.style.display = 'none';
        }
    });
});
