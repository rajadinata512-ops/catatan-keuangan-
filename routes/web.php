<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [TransaksiController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    Route::post('/transaksi', [TransaksiController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('transaksi.store');

    Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])
        ->name('transaksi.destroy');

    Route::delete('/transaksi', [TransaksiController::class, 'destroyAll'])
        ->middleware('throttle:5,1')
        ->name('transaksi.destroyAll');

    Route::get('/transaksi/export', [TransaksiController::class, 'export'])
        ->middleware('throttle:10,1')
        ->name('transaksi.export');
});

require __DIR__.'/auth.php';