document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); 

            const listingId = this.getAttribute('data-id');

            fetch(`/favorite/${listingId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect; 
                    return;
                }

                if (data.status === 'added') {
                    this.textContent = '♥';
                } else {
                    this.textContent = '♡';
                }
            })
            .catch(error => console.error('Ошибка:', error));
        });
    });
});


