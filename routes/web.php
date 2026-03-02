<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'index'])->name('home');
Route::get('/tables', [BookingController::class, 'tables'])->name('tables.index');
Route::get('/tables/{table}', [BookingController::class, 'showTable'])->name('tables.show');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
Route::get('/bookings/{reference}', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::post('/bookings/{reference}/review', [BookingController::class, 'storeReview'])->name('bookings.review');
Route::get('/reviews', [BookingController::class, 'reviews'])->name('reviews.index');
