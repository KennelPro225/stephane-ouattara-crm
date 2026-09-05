<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgrammeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/programmes', [ProgrammeController::class, 'index'])->name('programmes.index');
Route::get('/programmes/{programme:slug}', [ProgrammeController::class, 'show'])->name('programmes.show');
Route::get('/reserver-une-session', [BookingController::class, 'create'])->name('reserver-une-session');
Route::post('/reserver-une-session', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/programmes', [Admin\ProgrammeController::class, 'index'])->name('programmes.index');
    Route::get('/programmes/create', [Admin\ProgrammeController::class, 'create'])->name('programmes.create');
    Route::post('/programmes', [Admin\ProgrammeController::class, 'store'])->name('programmes.store');
    Route::get('/programmes/{programme}/edit', [Admin\ProgrammeController::class, 'edit'])->name('programmes.edit');
    Route::put('/programmes/{programme}', [Admin\ProgrammeController::class, 'update'])->name('programmes.update');
    Route::delete('/programmes/{programme}', [Admin\ProgrammeController::class, 'destroy'])->name('programmes.destroy');

    Route::get('/reservations', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/reservations/{booking}', [Admin\BookingController::class, 'update'])->name('bookings.update');

    Route::get('/clients', [Admin\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/clients/export', [Admin\CustomerController::class, 'export'])->name('customers.export');
    Route::get('/clients/{customer}', [Admin\CustomerController::class, 'show'])->name('customers.show');
    Route::patch('/clients/{customer}', [Admin\CustomerController::class, 'update'])->name('customers.update');
    Route::post('/clients/{customer}/notes', [Admin\CustomerController::class, 'addNote'])->name('customers.notes.store');

    Route::get('/disponibilites', [Admin\AvailabilityController::class, 'index'])->name('availability.index');
    Route::put('/disponibilites', [Admin\AvailabilityController::class, 'update'])->name('availability.update');

    Route::get('/contenus', [Admin\ContentController::class, 'index'])->name('content.index');
    Route::put('/contenus', [Admin\ContentController::class, 'updateSettings'])->name('content.update');
    Route::post('/contenus/images', [Admin\ContentController::class, 'updateImages'])->name('content.images');
    Route::put('/contenus/testimonials/{testimonial}', [Admin\ContentController::class, 'updateTestimonial'])->name('testimonials.update');
    Route::put('/contenus/gallery/{gallery}', [Admin\ContentController::class, 'updateGallery'])->name('gallery.update');
});
