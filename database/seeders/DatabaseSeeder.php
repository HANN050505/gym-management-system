<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Member;
use App\Models\PaketMembership;
use App\Models\Membership;
use App\Models\TransaksiMembership;
use App\Models\Absensi;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@gym.com'],
            [
                'name' => 'Admin Gym',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Paket Membership
        $paketHarian = PaketMembership::firstOrCreate(
            ['nama_paket' => 'Paket Harian (Daily Pass)'],
            [
                'durasi_hari' => 1,
                'harga' => 25000,
                'deskripsi' => 'Akses gym 1 hari untuk tamu insidental / latihan harian.',
                'status' => 'aktif',
            ]
        );

        $paket1 = PaketMembership::firstOrCreate(
            ['nama_paket' => 'Paket Bulanan'],
            [
                'durasi_hari' => 30,
                'harga' => 250000,
                'deskripsi' => 'Akses gym reguler 30 hari + semua fasilitas alat.',
                'status' => 'aktif',
            ]
        );

        $paket2 = PaketMembership::firstOrCreate(
            ['nama_paket' => 'Paket 3 Bulan (Hemat)'],
            [
                'durasi_hari' => 90,
                'harga' => 650000,
                'deskripsi' => 'Akses gym 90 hari + free program latihan awal.',
                'status' => 'aktif',
            ]
        );

        $paket3 = PaketMembership::firstOrCreate(
            ['nama_paket' => 'Paket Tahunan (VIP)'],
            [
                'durasi_hari' => 365,
                'harga' => 2200000,
                'deskripsi' => 'Akses gym unlimited 1 tahun + fasilitas locker pribadi.',
                'status' => 'aktif',
            ]
        );

        // 3. Member & Membership
        $member1 = Member::create([
            'kode_member' => 'MBR-2026-0001',
            'nama' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sudirman No. 45, Jakarta',
            'status' => 'aktif',
        ]);

        $member2 = Member::create([
            'kode_member' => 'MBR-2026-0002',
            'nama' => 'Siti Rahma',
            'email' => 'siti@example.com',
            'no_hp' => '082345678901',
            'alamat' => 'Jl. Merdeka No. 12, Bandung',
            'status' => 'aktif',
        ]);

        $member3 = Member::create([
            'kode_member' => 'MBR-2026-0003',
            'nama' => 'Andi Wijaya',
            'email' => 'andi@example.com',
            'no_hp' => '083456789012',
            'alamat' => 'Jl. Diponegoro No. 8, Surabaya',
            'status' => 'expired',
        ]);

        $member4 = Member::create([
            'kode_member' => 'MBR-2026-0004',
            'nama' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'no_hp' => '084567890123',
            'alamat' => 'Jl. Gatot Subroto No. 99, Medan',
            'status' => 'expired',
        ]);

        // Membership 1: Budi (Aktif, 30 hari mulai kemarin)
        $ms1 = Membership::create([
            'member_id' => $member1->id,
            'paket_membership_id' => $paket1->id,
            'tanggal_mulai' => Carbon::today()->subDays(5),
            'tanggal_berakhir' => Carbon::today()->addDays(25),
            'status' => 'aktif',
        ]);
        TransaksiMembership::create([
            'member_id' => $member1->id,
            'paket_membership_id' => $paket1->id,
            'membership_id' => $ms1->id,
            'total' => $paket1->harga,
            'metode_pembayaran' => 'cash',
            'status_pembayaran' => 'lunas',
            'catatan' => 'Pembayaran tunai di kasir',
            'tanggal_bayar' => Carbon::today()->subDays(5),
        ]);

        // Membership 2: Siti (Aktif tapi hampir habis - 2 hari lagi)
        $ms2 = Membership::create([
            'member_id' => $member2->id,
            'paket_membership_id' => $paket2->id,
            'tanggal_mulai' => Carbon::today()->subDays(88),
            'tanggal_berakhir' => Carbon::today()->addDays(2),
            'status' => 'aktif',
        ]);
        TransaksiMembership::create([
            'member_id' => $member2->id,
            'paket_membership_id' => $paket2->id,
            'membership_id' => $ms2->id,
            'total' => $paket2->harga,
            'metode_pembayaran' => 'transfer',
            'status_pembayaran' => 'lunas',
            'catatan' => 'Transfer Bank BCA',
            'tanggal_bayar' => Carbon::today()->subDays(88),
        ]);

        // Membership 3: Andi (Expired 10 hari lalu)
        $ms3 = Membership::create([
            'member_id' => $member3->id,
            'paket_membership_id' => $paket1->id,
            'tanggal_mulai' => Carbon::today()->subDays(40),
            'tanggal_berakhir' => Carbon::today()->subDays(10),
            'status' => 'expired',
        ]);
        TransaksiMembership::create([
            'member_id' => $member3->id,
            'paket_membership_id' => $paket1->id,
            'membership_id' => $ms3->id,
            'total' => $paket1->harga,
            'metode_pembayaran' => 'qris',
            'status_pembayaran' => 'lunas',
            'catatan' => 'QRIS Kasir',
            'tanggal_bayar' => Carbon::today()->subDays(40),
        ]);

        // 4. Sample Absensi
        Absensi::create([
            'member_id' => $member1->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam' => '08:30:00',
        ]);
        Absensi::create([
            'member_id' => $member2->id,
            'tanggal' => Carbon::today()->toDateString(),
            'jam' => '10:15:00',
        ]);
        Absensi::create([
            'member_id' => $member1->id,
            'tanggal' => Carbon::yesterday()->toDateString(),
            'jam' => '16:45:00',
        ]);

        // 5. Sample Notifikasi
        Notifikasi::create([
            'member_id' => $member2->id,
            'membership_id' => $ms2->id,
            'judul' => 'Masa Aktif Hampir Berakhir',
            'pesan' => 'Membership Siti Rahma tersisa 2 hari lagi (berakhir pada ' . Carbon::today()->addDays(2)->format('d M Y') . '). Silakan lakukan perpanjangan.',
            'tipe' => 'warning',
            'dibaca' => false,
        ]);
    }
}
