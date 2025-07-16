<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\HasilLabController;
use App\Http\Controllers\DetailTestController;
use App\Http\Controllers\PenerimaanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::resource('users', UserController::class)->middleware('auth');
Route::resource('dokters', DokterController::class);
Route::resource('ruangans', RuanganController::class);
Route::resource('tests', TestController::class);
Route::resource('detail_tests', DetailTestController::class);
Route::resource('vouchers', VoucherController::class);
Route::resource('pasiens', PasienController::class);
Route::get('/tests/{test}/details', [TestController::class, 'getDetails'])->name('tests.details');
Route::get('visits/sampling', [VisitController::class, 'sampling'])->name('visits.sampling');
Route::get('visits/pemeriksaan', [VisitController::class, 'pemeriksaan'])->name('visits.pemeriksaan');
Route::put('/visits/{visit}/status', [VisitController::class, 'updateStatus'])->name('visits.update-status');
Route::get('/visits/validasi', [VisitController::class, 'validasi'])->name('visits.validasi');
Route::get('/visits/cetak', [VisitController::class, 'cetak'])->name('visits.cetak');
Route::get('/visits/bayar', [VisitController::class, 'bayar'])->name('visits.bayar');
Route::get('visits/{id}/pembayaran', [VisitController::class, 'formPembayaran'])->name('visits.formPembayaran');
Route::post('visits/{id}/pembayaran', [VisitController::class, 'pembayaran'])->name('visits.pembayaran');
Route::get('laporan-pembayaran', [VisitController::class, 'laporanPembayaran'])->name('laporan.pembayaran');
Route::get('/hasil-lab/print/{id}', [App\Http\Controllers\HasilLabController::class, 'print'])->name('hasil-lab.print');

Route::resource('visits', VisitController::class);
Route::middleware(['auth'])->group(function () {
    Route::get('visits/{visit}/hasil-lab', [HasilLabController::class, 'edit'])->name('hasil-lab.edit');
    Route::put('visits/{visit}/hasil-lab', [HasilLabController::class, 'update'])->name('hasil-lab.update');
    Route::post('hasil-lab/{visit}/validate', [HasilLabController::class, 'validateResults'])->name('hasil-lab.validate');
    Route::post('hasil-lab/{visit}/unvalidate', [HasilLabController::class, 'unvalidateResults'])->name('hasil-lab.unvalidate');
    Route::post('hasil-lab/{visit}/valid', [HasilLabController::class, 'validateAdmin'])->name('hasil-lab.valid');
    Route::post('hasil-lab/{visit}/unvalid', [HasilLabController::class, 'unvalidateAdmin'])->name('hasil-lab.unvalid');
});
Route::post('penerimaan/store', [PenerimaanController::class, 'store'])->name('penerimaan.store');
