<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DailyClosingController;
use App\Http\Controllers\PemilikController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KasirController;



// Route Kasir
Route::middleware(['auth', 'role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::post('/add', [KasirController::class, 'addToCart'])->name('add');
    Route::post('/remove', [KasirController::class, 'removeFromCart'])->name('remove');
    Route::post('/checkout', [KasirController::class, 'checkout'])->name('checkout');
    Route::get('/riwayat', [KasirController::class, 'riwayatTransaksi'])->name('riwayat');
    Route::get('/struk/{id}', [KasirController::class, 'cetakStruk'])->name('struk');

    Route::get('/laporan-harian', [DailyClosingController::class, 'indexKasir'])->name('laporan');
    Route::post('/tutup-laporan', [DailyClosingController::class, 'closeToday'])->name('tutupLaporan');
});

// Route Pemilik
Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [PemilikController::class, 'laporan'])->name('laporan');
    Route::get('/laporan-cashflow', [DailyClosingController::class, 'indexPemilik'])->name('cashflow');
    Route::get('/laporan/cetak', [PemilikController::class, 'cetakLaporan'])->name('laporan.cetak');
    Route::get('/laporan-pdf', [PemilikController::class, 'cetakPDF'])->name('laporan.pdf');
});

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('/products', ProductController::class);
    Route::get('/laporan-penjualan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetakLaporan'])->name('laporan.cetak');
    Route::get('/laporan-pdf', [LaporanController::class, 'cetakPDF'])->name('laporan.pdf');
});

// Auth routes
Route::get('/', fn () => redirect('/login'));
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Auth::routes();
