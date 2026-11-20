
<?php

// ======================================================
// IMPORT CONTROLLER AUTENTIKASI
// ======================================================

// Controller untuk proses login dan logout.
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Controller untuk konfirmasi password pengguna.
use App\Http\Controllers\Auth\ConfirmablePasswordController;

// Controller untuk pengiriman ulang verifikasi email.
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

// Controller untuk menampilkan halaman pemberitahuan verifikasi email.
use App\Http\Controllers\Auth\EmailVerificationPromptController;

// Controller untuk membuat password baru.
use App\Http\Controllers\Auth\NewPasswordController;

// Controller untuk memperbarui password pengguna.
use App\Http\Controllers\Auth\PasswordController;

// Controller untuk permintaan reset password.
use App\Http\Controllers\Auth\PasswordResetLinkController;

// Controller registrasi tetap diimpor jika suatu saat
// registrasi akan diaktifkan kembali.
// Untuk saat ini route registrasi dinonaktifkan.
use App\Http\Controllers\Auth\RegisteredUserController;

// Controller untuk memverifikasi email.
use App\Http\Controllers\Auth\VerifyEmailController;

// Import Route Laravel.
use Illuminate\Support\Facades\Route;


// ======================================================
// ROUTE UNTUK PENGGUNA YANG BELUM LOGIN (GUEST)
// ======================================================

Route::middleware('guest')->group(function () {

    // --------------------------------------------------
    // REGISTRASI PUBLIK DINONAKTIFKAN SEMENTARA
    // --------------------------------------------------
    // Akun pengguna BLESS POS nantinya dibuat melalui
    // mekanisme administrasi yang memiliki hak akses.
    //
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');
    //
    // Route::post('register', [RegisteredUserController::class, 'store']);


    // --------------------------------------------------
    // LOGIN
    // --------------------------------------------------

    // Menampilkan halaman login.
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    // Memproses data login pengguna.
    Route::post('login', [AuthenticatedSessionController::class, 'store']);


    // --------------------------------------------------
    // LUPA PASSWORD
    // --------------------------------------------------

    // Menampilkan halaman permintaan reset password.
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    // Mengirimkan tautan reset password.
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // Menampilkan halaman untuk membuat password baru.
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    // Memproses penyimpanan password baru.
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});


// ======================================================
// ROUTE UNTUK PENGGUNA YANG SUDAH LOGIN (AUTH)
// ======================================================

Route::middleware('auth')->group(function () {

    // --------------------------------------------------
    // VERIFIKASI EMAIL
    // --------------------------------------------------

    // Menampilkan pemberitahuan verifikasi email.
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    // Memproses verifikasi email dengan tautan bertanda tangan.
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Mengirim ulang email verifikasi.
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');


    // --------------------------------------------------
    // KONFIRMASI DAN PEMBARUAN PASSWORD
    // --------------------------------------------------

    // Menampilkan halaman konfirmasi password.
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    // Memproses konfirmasi password.
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Memproses perubahan password pengguna.
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update');


    // --------------------------------------------------
    // LOGOUT
    // --------------------------------------------------

    // Mengakhiri sesi login pengguna.
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
