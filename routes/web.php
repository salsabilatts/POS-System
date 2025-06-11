<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PemilikController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\KasirController;



// ADMIN
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('products', App\Http\Controllers\ProductController::class);
});

// PEMILIK
Route::middleware(['auth', 'role:pemilik'])->group(function () {
    Route::get('/pemilik/dashboard', function () {
        return view('pemilik.dashboard');
    })->name('pemilik.dashboard');

Route::middleware(['auth', 'role:pemilik'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', [PemilikController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [PemilikController::class, 'laporan'])->name('laporan');
});

});

// Produk di page admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/product', ProductController::class);
});

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->middleware('auth', 'role:admin')->name('admin.dashboard');

// Route untuk halaman Laporan Penjualan (harian)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/laporan-penjualan', [LaporanController::class, 'index'])
         ->name('laporan.index');
});

//route untuk menu kasir
Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/add', [KasirController::class, 'addToCart'])->name('kasir.add');
    Route::post('/kasir/remove', [KasirController::class, 'removeFromCart'])->name('kasir.remove');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/kasir/riwayat', [KasirController::class, 'riwayatTransaksi'])->name('kasir.riwayat');
    Route::get('/kasir/struk/{id}', [KasirController::class, 'cetakStruk'])->name('kasir.struk');

});

Route::get('/admin/laporan/cetak', [LaporanController::class, 'cetakLaporan'])->name('admin.laporan.cetak');
Route::get('/pemilik/laporan/cetak', [PemilikController::class, 'cetakLaporan'])->name('pemilik.laporan.cetak');

Route::get('/laporan-pdf', [PemilikController::class, 'cetakPDF'])->name('pemilik.laporan.pdf');
Route::get('/admin/laporan-pdf', [LaporanController::class, 'cetakPDF'])->name('admin.laporan.pdf');

// Kalau pakai Laravel Breeze, Fortify, atau Jetstream, ini biasanya sudah otomatis
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


