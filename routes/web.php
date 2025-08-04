<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\HasilLabController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DetailTestController;
use App\Http\Controllers\JasmaniMcuController;
use App\Http\Controllers\PenerimaanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
Route::get('/pasien/card/{norm}', [PasienController::class, 'cetakKartuByNorm'])->name('pasiens.card');
Route::get('/pasien/label/{norm}', [PasienController::class, 'cetakLabelIdentitas'])->name('pasiens.label');
// Route::get('/pasien/riwayat/{norm}', [PasienController::class, 'cetakRiwayat'])->name('pasiens.riwayat');
Route::get('/pasien/{norm}/riwayat', [HasilLabController::class, 'cetakRiwayat'])->name('hasil-lab.riwayat');
Route::resource('pasiens', PasienController::class);
Route::get('/tests/{test}/details', [TestController::class, 'getDetails'])->name('tests.details');
Route::get('api/pakets/{paket}/tests', [VisitController::class, 'getPaketTests'])->name('api.pakets.tests');
Route::get('visits/sampling', [VisitController::class, 'sampling'])->name('visits.sampling');
Route::get('visits/barcode', [VisitController::class, 'barcodesampling'])->name('visits.barcode');
Route::get('visits/pemeriksaan', [VisitController::class, 'pemeriksaan'])->name('visits.pemeriksaan');
Route::get('/pemeriksaan/hematologi', [VisitController::class, 'Hematologi'])->name('pemeriksaan.hematologi');
Route::get('/pemeriksaan/kimiaklinik', [VisitController::class, 'KimiaKlinik'])->name('pemeriksaan.kimiaklinik');
Route::get('/pemeriksaan/imunologiserologi', [VisitController::class, 'imunologiSerologi'])->name('pemeriksaan.imunologiserologi');
Route::get('/pemeriksaan/mikrobiologi', [VisitController::class, 'mikrobiologi'])->name('pemeriksaan.mikrobiologi');
Route::get('/pemeriksaan/khusus', [VisitController::class, 'khusus'])->name('pemeriksaan.khusus');
Route::get('/pemeriksaan/lainnya', [VisitController::class, 'lainnya'])->name('pemeriksaan.lainnya');
Route::get('/pemeriksaan/paket', [VisitController::class, 'Paket'])->name('pemeriksaan.paket');
Route::put('/visits/{visit}/status', [VisitController::class, 'updateStatus'])->name('visits.update-status');
Route::get('/visits/validasi', [VisitController::class, 'validasi'])->name('visits.validasi');
Route::get('/visits/cetak', [VisitController::class, 'cetak'])->name('visits.cetak');
Route::get('/visits/bayar', [VisitController::class, 'bayar'])->name('visits.bayar');
Route::get('visits/{id}/pembayaran', [VisitController::class, 'formPembayaran'])->name('visits.formPembayaran');
Route::post('visits/{id}/pembayaran', [VisitController::class, 'pembayaran'])->name('visits.pembayaran');
Route::get('/visits/label/{no_order}', [VisitController::class, 'cetakLabel'])->name('visits.cetak.label');
Route::get('/visits/nota/{no_order}', [VisitController::class, 'cetakNota'])->name('visits.cetak.nota');
Route::get('/visits/barcode/{no_order}', [VisitController::class, 'cetakBarcode'])->name('visits.cetak.barcode');
Route::get('/visits/{id}/proses', [VisitController::class, 'proses'])->name('visits.proses');
Route::get('/visits/laporan-pembayaran/excel', [VisitController::class, 'exportLaporanPembayaranExcel'])->name('visits.export.excel');
Route::get('/visits/laporan-pembayaran/pdf', [VisitController::class, 'exportLaporanPembayaranPdf'])->name('visits.export.pdf');
Route::get('/visits/laporan-tahunan/excel', [VisitController::class, 'exportLaporanTahunanExcel'])->name('visits.export.tahunan.excel');
Route::get('laporan-pembayaran', [VisitController::class, 'laporanPembayaran'])->name('laporan.pembayaran');
Route::get('visits/laporan-tahunan', [VisitController::class, 'laporanTahunan'])->name('visits.laporan.tahunan');
Route::get('/hasil-lab/print/{no_order}', [HasilLabController::class, 'print'])->name('hasil-lab.print');
Route::get('/hasil-lab/download/{hash}', [HasilLabController::class, 'downloadByHash']);
Route::get('/laporan-kasir-harian', [VisitController::class, 'laporanKasirHarian'])->name('visits.laporanKasirHarian');
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
Route::resource('pakets', PaketController::class);
Route::get('/visits/{visitTest}/jasmani-mcu/create', [JasmaniMcuController::class, 'create'])->name('jasmani-mcu.create');
Route::post('/visits/{visitTest}/jasmani-mcu', [JasmaniMcuController::class, 'store'])->name('jasmani-mcu.store');
Route::get('/jasmani-mcu/{jasmaniMcu}', [JasmaniMcuController::class, 'show'])->name('jasmani-mcu.show');
Route::get('/jasmani-mcu/{jasmaniMcu}/edit', [JasmaniMcuController::class, 'edit'])->name('jasmani-mcu.edit');
Route::put('/jasmani-mcu/{jasmaniMcu}', [JasmaniMcuController::class, 'update'])->name('jasmani-mcu.update');
Route::delete('/jasmani-mcu/{jasmaniMcu}', [JasmaniMcuController::class, 'destroy'])->name('jasmani-mcu.destroy');
Route::get('/jasmani-mcu/{jasmaniMcu}/print', [JasmaniMcuController::class, 'printMcu'])->name('jasmani-mcu.print');
Route::get('/jasmani-mcu', [JasmaniMcuController::class, 'index'])->name('jasmani-mcu.index');
Route::get('/backup', [\App\Http\Controllers\BackupController::class, 'backup']);
