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
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard-gym');
});

/*
|--------------------------------------------------------------------------
| Authenticated Gym Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard-gym', [DashboardController::class, 'index'])->name('dashboard-gym');
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard-gym');
    })->name('dashboard');

    // Master Data
    Route::get('/members/{member}/kartu', [MemberController::class, 'kartu'])->name('members.kartu');
    Route::resource('members', MemberController::class);
    Route::resource('paket-memberships', PaketMembershipController::class);

    // Transaksi Membership
    Route::get('/transaksi-membership/{transaksi}/struk', [TransaksiMembershipController::class, 'struk'])->name('transaksi-membership.struk');
    Route::get('/transaksi-membership', [TransaksiMembershipController::class, 'index'])->name('transaksi-membership.index');
    Route::post('/transaksi-membership', [TransaksiMembershipController::class, 'store'])->name('transaksi-membership.store');

    // Absensi QR
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/riwayat-absensi', [AbsensiController::class, 'riwayat'])->name('absensi.riwayat');

    // Laporan
    Route::get('/laporan-member', [LaporanController::class, 'member'])->name('laporan.member');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/generate', [NotifikasiController::class, 'generate'])->name('notifikasi.generate');
    Route::patch('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllRead');

    // Breeze Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
