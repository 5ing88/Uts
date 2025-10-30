<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtsController;

Route::get('/', [UtsController::class, 'index'])->name('dashboard');

Route::post('/transaksi/store', [UtsController::class, 'store'])->name('transaksi.store');

Route::post('/pelanggan/store', [UtsController::class, 'storePelanggan'])->name('pelanggan.store');