<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PencairanController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Marketing\MarketingController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('anggota', AnggotaController::class);
    Route::post('/anggota/{id}/upload', [AnggotaController::class, 'upload'])->name('anggota.upload');
    Route::post('/anggota/{id}/lock', [AnggotaController::class, 'lock'])->name('anggota.lock');
    
    Route::resource('pencairan', PencairanController::class);
    Route::get('/get-pinjaman-ke/{anggotaId}', [PencairanController::class, 'getPinjamanKe'])->name('get.pinjaman.ke');
    Route::get('/search-anggota', [PencairanController::class, 'searchAnggota'])->name('search.anggota');
    Route::post('/pencairan/{id}/upload', [PencairanController::class, 'upload'])->name('pencairan.upload');
    Route::post('/pencairan/{id}/lock', [PencairanController::class, 'lock'])->name('pencairan.lock');
    
    Route::resource('simpanan', SimpananController::class);
});

require __DIR__ . '/auth.php';
Route::middleware('auth', 'MarketingMiddleware')->group(function () {
    Route::get('/dashboard', [MarketingController::class, 'index'])->name('marketing.dashboard');
});

Route::middleware('auth', 'AdminMiddleware')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});
