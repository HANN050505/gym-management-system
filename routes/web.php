<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PaketMembershipController;
use App\Http\Controllers\TransaksiMembershipController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Dashboard Gym (Halaman Utama)
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/dashboard-gym');

Route::get(
    '/dashboard-gym',
    [DashboardController::class, 'index']
)->name('dashboard-gym');

/*
|--------------------------------------------------------------------------
| Master Data
|--------------------------------------------------------------------------
*/

Route::resource(
    'members',
    MemberController::class
);

Route::resource(
    'paket-memberships',
    PaketMembershipController::class
);

/*
|--------------------------------------------------------------------------
| Transaksi Membership
|--------------------------------------------------------------------------
*/

Route::get(
    '/transaksi-membership',
    [TransaksiMembershipController::class, 'index']
);

Route::post(
    '/transaksi-membership',
    [TransaksiMembershipController::class, 'store']
);

/*
|--------------------------------------------------------------------------
| Absensi QR
|--------------------------------------------------------------------------
*/

Route::get(
    '/absensi',
    [AbsensiController::class, 'index']
);

Route::post(
    '/absensi',
    [AbsensiController::class, 'store']
);

Route::get(
    '/riwayat-absensi',
    [AbsensiController::class, 'riwayat']
);

/*
|--------------------------------------------------------------------------
| Laporan & Notifikasi
|--------------------------------------------------------------------------
*/

Route::get(
    '/laporan-member',
    [LaporanController::class, 'member']
);

Route::get(
    '/notifikasi',
    [NotifikasiController::class, 'index']
);

/*
|--------------------------------------------------------------------------
| Laravel Breeze
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])
  ->name('dashboard');

require __DIR__.'/auth.php';