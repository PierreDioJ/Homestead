document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("bookingsModal");
    const btn = document.querySelector(".btn-bookings");
    const closeBtn = modal.querySelector(".btn-close");

    btn.addEventListener("click", function () {
        modal.style.display = "flex";
    });

    closeBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});
