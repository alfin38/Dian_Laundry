<?php

use App\Http\Controllers\AdminPaymentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaketController;
use App\Models\Paket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/paket', [PaketController::class, 'index'])->name('paket.index');

//cuci komplit route
Route::get('/paket/{jenis_paket}', [PaketController::class, 'list_paket'])->name('paket.list');
Route::get('/paket/{jenis_paket}/create', [PaketController::class, 'create'])->name('paket.create');
Route::post('/paket/{jenis_paket}/store', [PaketController::class, 'store'])->name('paket.store');
Route::get('/paket/{jenis_paket}/edit/{id}', [PaketController::class, 'edit'])->name('paket.edit');
Route::put('/paket/{jenis_paket}/update/{id}',[PaketController::class, 'update'])->name('paket.update');
Route::delete('/paket/{jenis_paket}/delete/{id}',[PaketController::class, 'destroy'])->name('paket.destroy');

Route::get('/order', [OrderController::class, 'index'])->name('order.index');

Route::get('/order/{jenis_paket}', [OrderController::class, 'create'])->name('order.create');
Route::post('/order/{jenis_paket}/store', [OrderController::class, 'store'])->name('order.store');
Route::get('/order/{jenis_paket}/detail/{id}', [OrderController::class, 'show'])->name('order.show');
Route::get('/order/invoice/{id}', [OrderController::class, 'showInvoice'])->name('order.invoice');
Route::delete('/order/delete/{id}', [OrderController::class, 'destroy'])->name('order.destroy');

// Action Routes
Route::post('/order/{id}/bayar', [OrderController::class, 'processPayment'])->name('payment.process');
Route::post('/order/{id}/bayar-nanti', [OrderController::class, 'payLater'])->name('order.payLater');
Route::post('/order/{id}/siap-diambil', [OrderController::class, 'markAsReadyForPickup'])->name('order.ready');
Route::post('/order/{id}/selesai', [OrderController::class, 'markAsCompleted'])->name('order.complete');
Route::post('/order/{id}/terlambat', [OrderController::class, 'markAsDelayed'])->name('order.delay');

Route::get('/riwayat', [OrderController::class, 'riwayat'])->name('riwayat.index');
Route::get('/laporan-keuangan', [OrderController::class, 'laporanKeuangan'])->name('laporan.index');
Route::get('/laporan-keuangan/print', [OrderController::class, 'laporanKeuanganPrint'])->name('laporan.print');


Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
Route::get('/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
Route::post('/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::get('/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
Route::put('/karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/karyawan/delete/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');

// Midtrans notification route
Route::post('/midtrans/notification', [OrderController::class, 'notificationHandler'])->name('midtrans.notification');

Route::post('/order/{id}/pay-midtrans', [OrderController::class, 'processMidtransPayment'])->name('payment.process.midtrans');
Route::get('/api/check-order-date', [OrderController::class, 'checkOrderDate'])->name('api.checkOrderDate');

Route::post('/payment/process/cash/{order}', [OrderController::class, 'processCashPayment'])->name('payment.process.cash');
