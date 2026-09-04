<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/programmes', [ProgrammeController::class, 'index'])->name('programmes.index');
Route::get('/programmes/{slug}', [ProgrammeController::class, 'show'])->name('programmes.show');

Route::get('/reserver-une-session', [SessionController::class, 'create'])->name('reserver-une-session');
Route::post('/reserver-une-session', [SessionController::class, 'store'])->name('sessions.store');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Compatibilité : anciennes pages fusionnées dans la one-page et /programmes
Route::redirect('/adolescents', '/#realisations', 301);
Route::redirect('/entreprises', '/#services', 301);

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', App\Http\Controllers\Auth\AuthenticatedSessionController::class.'@create')->name('login');
        Route::post('/login', App\Http\Controllers\Auth\AuthenticatedSessionController::class.'@store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics', [Admin\AnalyticsController::class, 'index'])->name('analytics');

        Route::get('/content', [Admin\ContentController::class, 'index'])->name('content');
        Route::put('/content', [Admin\ContentController::class, 'updateSettings'])->name('content.update');
        Route::post('/content/testimonials', [Admin\ContentController::class, 'storeTestimonial'])->name('testimonials.store');
        Route::put('/content/testimonials/{testimonial}', [Admin\ContentController::class, 'updateTestimonial'])->name('testimonials.update');
        Route::delete('/content/testimonials/{testimonial}', [Admin\ContentController::class, 'destroyTestimonial'])->name('testimonials.destroy');
        Route::post('/content/galleries', [Admin\ContentController::class, 'storeGallery'])->name('galleries.store');
        Route::delete('/content/galleries/{gallery}', [Admin\ContentController::class, 'destroyGallery'])->name('galleries.destroy');

        Route::resource('programmes', Admin\ProgrammeController::class)
            ->parameters(['programmes' => 'programme'])
            ->except(['show']);

        Route::get('/customers', [Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/export', [Admin\CustomerController::class, 'export'])->name('customers.export');
        Route::get('/customers/{customer}', [Admin\CustomerController::class, 'show'])->name('customers.show');

        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [Admin\UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');
    });
});
