<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
//marketing route
Route::middleware('auth', 'MarketingMiddleware')->group(function(){
    Route::get('/dashboard', [MarketingController::class, 'index'])->name('marketing.dashboard');
});

// admin route
Route::middleware('auth', 'AdminMiddleware')->group(function(){
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});


Route::prefix('anggota')->name('anggota.')->group(function() {
    // Route untuk menampilkan semua anggota
    Route::get('/', [AnggotaController::class, 'index'])->name('index');
    
    // Route untuk menampilkan form untuk membuat anggota baru
    Route::get('/create', [AnggotaController::class, 'create'])->name('create');
    
    // Route untuk menyimpan anggota baru
    Route::post('/', [AnggotaController::class, 'store'])->name('store');
    
    // Route untuk menampilkan form edit anggota
    Route::get('{anggota}/edit', [AnggotaController::class, 'edit'])->name('edit');
    
    // Route untuk memperbarui anggota
    Route::put('{anggota}', [AnggotaController::class, 'update'])->name('update');
    
    // Route untuk menghapus anggota
    Route::delete('{anggota}', [AnggotaController::class, 'destroy'])->name('destroy');
    
    // Route untuk menampilkan detail anggota (optional)
    Route::get('{anggota}', [AnggotaController::class, 'show'])->name('show');
});


