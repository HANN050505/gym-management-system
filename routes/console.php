<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\NotifikasiController;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('gym:check-expiry', function () {
    $controller = app(NotifikasiController::class);
    $count = $controller->cekMembershipHampirHabisDanExpired();
    $this->info("Pengecekan selesai: {$count} status membership diperbarui dan notifikasi dikirimkan.");
})->purpose('Periksa masa aktif keanggotaan DOEL GYM dan perbarui status expired');

