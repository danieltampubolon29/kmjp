<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgresController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PencairanController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\KasbonHarianMarketingController;
use App\Http\Controllers\Marketing\MarketingController;
use App\Http\Controllers\ValidasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    // validasi
    Route::resource('validasi',ValidasiController::class);
        // pencairan 
    Route::get('/validasi-pencairan', [ValidasiController::class, 'pencairan'])->name('validasi.pencairan');
    Route::post('/validasi/pencairan', [ValidasiController::class, 'validasiPencairan'])->name('validasi.semua-pencairan');
        // angsuran
    Route::get('/validasi-angsuran', [ValidasiController::class, 'angsuran'])->name('validasi.angsuran');
    Route::post('/validasi/angsuran', [ValidasiController::class, 'validasiAngsuran'])->name('validasi.semua-angsuran');
        // simpanan
    Route::get('/validasi-simpanan', [ValidasiController::class, 'simpanan'])->name('validasi.simpanan');
    Route::post('/validasi/simpanan', [ValidasiController::class, 'validasiSimpanan'])->name('validasi.semua-simpanan');


    // kasbon
    Route::resource('kasbon',KasbonHarianMarketingController::class);
    Route::put('/kasbon/status/{id}', [KasbonHarianMarketingController::class, 'updateStatus'])->name('kasbon.status');
    Route::post('/kasbon/{id}/lock', [KasbonHarianMarketingController::class, 'lock'])->name('kasbon.lock');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // route anggota
    Route::resource('anggota', AnggotaController::class);
    Route::post('/anggota/{id}/upload', [AnggotaController::class, 'upload'])->name('anggota.upload');
    Route::post('/anggota/{id}/lock', [AnggotaController::class, 'lock'])->name('anggota.lock');

    // route pencairan
    Route::resource('pencairan', PencairanController::class);
    Route::get('/get-pinjaman-ke/{anggotaId}', [PencairanController::class, 'getPinjamanKe'])->name('get.pinjaman.ke');
    Route::get('/search-anggota', [PencairanController::class, 'searchAnggota'])->name('search.anggota');
    Route::post('/pencairan/{id}/upload', [PencairanController::class, 'upload'])->name('pencairan.upload');
    Route::post('/pencairan/{id}/lock', [PencairanController::class, 'lock'])->name('pencairan.lock');

    // route simpanan
    Route::resource('simpanan', SimpananController::class);
    Route::post('/simpanan/{id}/lock', [SimpananController::class, 'lock'])->name('simpanan.lock');

    // route angsuran
    Route::resource('angsuran', AngsuranController::class);
    Route::post('/angsuran/{id}/lock', [AngsuranController::class, 'lock'])->name('angsuran.lock');
    Route::get('/search-pencairan', [AngsuranController::class, 'searchPencairan'])->name('search.pencairan');
    
    // progres
        // route rekap data 
    Route::get('/rekap-data', [ProgresController::class, 'rekapData'])->name('progres.rekap-data');
    Route::get('/rekap-data/get-pencairan-data', [ProgresController::class, 'getPencairanData'])->name('progres.get-pencairan-data');
        // marketing 
    Route::get('/rekap-marketing', [ProgresController::class, 'rekapMarketing'])->name('progres.rekap-marketing');
    Route::get('/rekap-data/get-marketing-data', [ProgresController::class, 'getRekapData'])->name('progres.get-marketing-data');
    // target harian
    Route::get('/target-harian', [ProgresController::class, 'targetHarian'])->name('progres.target-harian');



    // route dashboard marketing cek data
    Route::get('/get-simpanan-data', [SimpananController::class, 'getSimpananData'])->name('get.simpanan.data');
    Route::get('/get-simpanan-transactions', [SimpananController::class, 'getTransactions']);
    Route::get('/get-pencairan-data', [PencairanController::class, 'getPencairanData'])->name('get.pencairan.data');
    Route::get('/get-angsuran-data/{pencairanId}', [AngsuranController::class, 'getAngsuranData']);

    // route laporan

        // angsuran 
    Route::get('/laporan-angsuran', [LaporanController::class, 'angsuran'])->name('laporan.angsuran');
    Route::post('/laporan/get-angsuran-by-date', [LaporanController::class, 'getAngsuranByDate']);
        
        // pencairan
    Route::get('/laporan-pencairan', [LaporanController::class, 'pencairan'])->name('laporan.pencairan');
    Route::post('/laporan/get-pencairan-by-date', [LaporanController::class, 'getPencairanByDate']);

        // harian
    Route::get('/laporan-harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::post('/laporan/get-filtered-data', [LaporanController::class, 'getFilteredData'])->name('laporan.getFilteredData');
});

require __DIR__ . '/auth.php';
Route::middleware('auth', 'MarketingMiddleware')->group(function () {
    Route::get('/dashboard', [MarketingController::class, 'index'])->name('marketing.dashboard');
});

Route::middleware('auth', 'AdminMiddleware')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

