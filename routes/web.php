<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', [TransaksiController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::delete('/hapus-semua', [TransaksiController::class, 'hapusSemua'])->middleware('auth');

Route::post('/transaksi', [TransaksiController::class, 'store'])->middleware('auth');

Route::get('/export', [TransaksiController::class, 'export'])->middleware('auth');

require __DIR__.'/auth.php';