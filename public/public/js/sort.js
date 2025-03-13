document.addEventListener('DOMContentLoaded', function () {
    const sortSelect = document.getElementById('sort');

    sortSelect.addEventListener('change', function () {
        const selectedSort = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', selectedSort);
        window.location.href = url.toString(); // Обновляем страницу с параметром сортировки
    });
});
