<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ReservationController::class, 'index'])->name('portal.index');
Route::post('/', [ReservationController::class, 'handlePost'])->name('portal.post');
Route::post('/process', [ReservationController::class, 'processBooking'])->name('portal.process');

Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::post('/admin/dashboard', [AdminController::class, 'handleAction'])->name('admin.dashboard.action');