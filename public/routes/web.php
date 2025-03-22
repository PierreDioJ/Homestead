<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Models\Listing;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminStatsController;


// Главная страница
Route::get('/', function () {
    return view('welcome');
});

// Личный кабинет
Route::get('/profile', [UserController::class, 'showProfile'])->middleware('auth')->name('profile');

// Обновление данных пользователя
Route::post('/profile', [UserController::class, 'updateProfile'])->middleware('auth');

// Выход из аккаунта
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Авторизация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Регистрация
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::resource('reviews', ReviewController::class);
Route::resource('payments', PaymentController::class);
Route::resource('bookings', BookingController::class);

Route::get('/', function () {
    // Получаем все объявления для отображения на главной странице
    $listings = Listing::latest()->take(10)->get(); // Возьмем последние 10 объявлений
    return view('welcome', compact('listings'));
});

// Главная страница
Route::get('/', [ListingController::class, 'index'])->name('listings.index');

// Страница фильтрации (тот же маршрут, что и главная, но с передачей параметров)
Route::get('/filter', [ListingController::class, 'index'])->name('listings.filter');


Route::get('/listings', [ListingController::class, 'index'])->name('listings.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
});

Route::middleware(['auth'])->get('/listings/create', [ListingController::class, 'create'])->name('listings.create');

Route::get('/listings/{id}', [ListingController::class, 'show'])->name('listings.show');

Route::post('/reports', [ReportController::class, 'store']);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

Route::get('/listings/{id}/edit', [ListingController::class, 'edit'])->name('listings.edit')->middleware('auth');
Route::put('/listings/{id}', [ListingController::class, 'update'])->name('listings.update')->middleware('auth');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile')->middleware('auth');
Route::post('/favorite/{id}', [FavoriteController::class, 'toggleFavorite'])->name('favorite.toggle');
Route::get('/favorites', [FavoriteController::class, 'index'])->middleware('auth');
Route::get('/favorites', [FavoriteController::class, 'getFavorites'])->name('favorites.get');
Route::post('/listings/{id}/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

Route::middleware(['auth'])->group(function () {
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{id}/upload-receipt', [BookingController::class, 'uploadReceipt'])->name('bookings.uploadReceipt');
    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    Route::get('/bookings/my', [BookingController::class, 'userBookings'])->name('bookings.my');
    Route::get('/bookings/landlord', [BookingController::class, 'landlordBookings'])->name('bookings.landlord');
});

// Автоотмена бронирований по крон-задаче
Route::get('/bookings/auto-cancel', [BookingController::class, 'autoCancelExpiredBookings']);

Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');

// Показ всех чатов пользователя
Route::get('/chats', [ChatController::class, 'userChats'])->name('chat.list');

// Отображение списка чатов и выбранного чата
Route::get('/chat/{id?}', [ChatController::class, 'index'])->name('chat.show');

// Отправка сообщений
Route::post('/chat/{id}/send', [ChatController::class, 'sendMessage'])->name('chat.send');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    // Пользователи
    Route::get('users', [AdminController::class, 'users'])->name('admin.users.index');
    Route::put('users/{user}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('users/{user}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');

    // Объявления
    Route::get('listings', [AdminController::class, 'listings'])->name('admin.listings.index');
    Route::post('listings/{listing}/update', [AdminController::class, 'updateListing'])->name('admin.listings.update');
    Route::delete('listings/{listing}/delete', [AdminController::class, 'deleteListing'])->name('admin.listings.delete');

    // Бронирования
    Route::get('bookings', [AdminController::class, 'bookings'])->name('admin.bookings.index');
    Route::post('bookings/{id}/update', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::post('bookings/{id}/delete', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');


    // Платежи
    Route::get('payments', [AdminController::class, 'payments'])->name('admin.payments.index');
    Route::post('payments/{id}/update', [AdminController::class, 'updatePayment'])->name('admin.payments.update');
    Route::post('payments/{id}/delete', [AdminController::class, 'deletePayment'])->name('admin.payments.delete');

    // Статистика и аналитика
    Route::get('stats', [AdminStatsController::class, 'index'])->name('admin.stats.index');

    // Репорты
    Route::get('reports', [AdminController::class, 'reports'])->name('admin.reports.index');
    Route::delete('reports/{reportId}/close', [AdminController::class, 'closeReport'])->name('admin.reports.close');
    Route::delete('reports/{reportId}/delete', [AdminController::class, 'deleteReport'])->name('admin.reports.delete');

    // Страница со списком всех отзывов
    Route::get('reviews', [AdminController::class, 'reviews'])->name('admin.reviews.index');

    // Удаление отзыва
    Route::delete('reviews/{reviewId}/delete', [AdminController::class, 'deleteReview'])->name('admin.reviews.delete');
});

Route::delete('/listings/{id}', [ListingController::class, 'delete'])->name('listings.delete');

// Показать форму восстановления пароля
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');

// Обработать восстановление пароля
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');






















