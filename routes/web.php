<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\ProjectTypeController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\TeamMemberPaymentController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Client Routes
    Route::resource('clients', ClientController::class);
    
    // Service Routes
    Route::resource('services', ServiceController::class);
    
    // Payment Routes
    Route::resource('payments', PaymentController::class);

    // Service Types & Project Types (Settings)
    Route::resource('service-types', ServiceTypeController::class)->except(['show']);
    Route::resource('project-types', ProjectTypeController::class)->except(['show']);

    // Team Members & Payments to Team
    Route::resource('team-members', TeamMemberController::class);
    Route::get('team-member-payments', [TeamMemberPaymentController::class, 'index'])->name('team-member-payments.index');
    Route::get('team-member-payments/create', [TeamMemberPaymentController::class, 'create'])->name('team-member-payments.create');
    Route::post('team-member-payments', [TeamMemberPaymentController::class, 'store'])->name('team-member-payments.store');
    Route::delete('team-member-payments/{teamMemberPayment}', [TeamMemberPaymentController::class, 'destroy'])->name('team-member-payments.destroy');
});
