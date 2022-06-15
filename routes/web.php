<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DebiturController;
use App\Http\Controllers\EdulevelController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\KelolauserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengobatanController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// User Authentication Routes
Route::get('/', [LoginController::class, 'index'])->middleware('guest')->name('/');
Route::get('/login', function () {
    return redirect('/');
})->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// User Registration Routes
Route::get('/registrasi', [RegistrasiController::class, 'index'])->middleware('guest');
Route::post('/registrasi', [RegistrasiController::class, 'store']);

// Debitur Routes
Route::get('/debitur', [DebiturController::class, 'index'])->middleware('auth');
Route::get('/debitur-api', [DebiturController::class, 'api'])->middleware('auth');
Route::post('/debitur', [DebiturController::class, 'store'])->middleware('auth');
Route::post('/update-debitur', [DebiturController::class, 'update'])->middleware('auth');
Route::delete('/debitur/{id}', [DebiturController::class, 'destroy'])->middleware('auth');

// Jenis Pengobatan Routes
Route::get('/jenis-pengobatan', [PengobatanController::class, 'index'])->middleware('auth');
Route::get('/pengobatan-api',   [PengobatanController::class, 'api'])->middleware('auth');
Route::post('/jenis-pengobatan', [PengobatanController::class, 'store'])->middleware('auth');
Route::post('/update-jenis-pengobatan', [PengobatanController::class, 'update'])->middleware('auth');
Route::delete('/jenis-pengobatan/{id}', [PengobatanController::class, 'destroy'])->middleware('auth');

// Data Akun Routes
Route::get('/akun',         [AkunController::class, 'index'])->middleware('auth');
Route::get('/akun-api',     [AkunController::class, 'api'])->middleware('auth');
Route::post('/akun',        [AkunController::class, 'store'])->middleware('auth');
Route::post('/update-akun', [AkunController::class, 'update'])->middleware('auth');
Route::delete('/akun/{id}', [AkunController::class, 'destroy'])->middleware('auth');

//Data Piutang
Route::get('/piutang',         [PiutangController::class, 'index'])->middleware('auth');
Route::get('/piutang-api',     [PiutangController::class, 'api'])->middleware('auth');
Route::post('/piutang',        [PiutangController::class, 'store'])->middleware('auth');
Route::delete('/piutang/{id}', [PiutangController::class, 'destroy'])->middleware('auth');
Route::post('/edit-piutang',   [PiutangController::class, 'update'])->middleware('auth');

Route::get('/cetak-piutang',   [CetakController::class, 'printSurat'])->middleware('auth');
Route::post('/cetak-invoice',  [CetakController::class, 'printInvoice'])->middleware('auth');
Route::post('/cetak-kwitansi', [CetakController::class, 'printKwitansi'])->middleware('auth');

Route::get('/mock',            [CetakController::class, 'mock'])->middleware('auth');

Route::get('/invoice',         [InvoiceController::class, 'index'])->middleware('auth');
Route::get('/invoice-api',     [InvoiceController::class, 'api'])->middleware('auth');
Route::post('/invoice',        [InvoiceController::class, 'store'])->middleware('auth');
Route::post('/invoice-delete', [InvoiceController::class, 'destroy'])->middleware('auth');

Route::get('/users',            [KelolauserController::class, 'index'])->middleware('auth');
Route::post('/user',            [KelolauserController::class, 'store'])->middleware('auth');
Route::post('/update-user',     [KelolauserController::class, 'update'])->middleware('auth');
Route::delete('/user/{id}',     [KelolauserController::class, 'destroy'])->middleware('auth');

Route::get('/pembayaran',       [PembayaranController::class, 'index'])->middleware('auth');
Route::get('/status-checker',   [PembayaranController::class, 'status_checker'])->middleware('auth');
Route::get('/detail-pembayaran',[PembayaranController::class, 'detail_pembayaran'])->middleware('auth');
Route::post('/pay-transaction', [PembayaranController::class, 'store'])->middleware('auth');
Route::post('/hapus-detail-pembayaran',[PembayaranController::class, 'destroy_detail'])->middleware('auth');

Route::get('/get-total-tagihan',[PembayaranController::class, 'getTotalTagihan'])->middleware('auth');
Route::post('/create-payment', [PembayaranController::class, 'storePayment'])->middleware('auth');

Route::get('/rekap-piutang',[RekapController::class, 'rekap_piutang'])->middleware('auth');
Route::post('/get-piutang-bulan-lalu',[RekapController::class, 'getRekapPiutangBulanLalu'])->middleware('auth');

Route::get('/tester',[PembayaranController::class, 'tester'])->middleware('auth');
Route::get('/rekap-umur-piutang',[RekapController::class, 'rekap_umur_piutang'])->middleware('auth');
Route::post('/rekap-umur-piutang-after',[RekapController::class, 'rekapUmurPiutangAfter'])->middleware('auth');
Route::post('/cetak-umur-piutang',[CetakController::class, 'printUmurPiutang'])->middleware('auth');
Route::get('/journal-tester',[PembayaranController::class, 'getLastJournalId']);

Route::get('/jurnal',[JurnalController::class, 'index'])->middleware('auth');
Route::post('/jurnal-after-search',[JurnalController::class, 'afterSearch'])->middleware('auth');