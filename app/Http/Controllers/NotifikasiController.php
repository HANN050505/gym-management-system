<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Notifikasi;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        // Auto cek membership saat buka halaman notifikasi
        $this->cekMembershipHampirHabisDanExpired();

        $notifikasis = Notifikasi::with(['member', 'membership'])
            ->latest()
            ->paginate(15);

        $unreadCount = Notifikasi::where('dibaca', false)->count();

        return view('notifikasi.index', compact('notifikasis', 'unreadCount'));
    }

    public function generate()
    {
        $count = $this->cekMembershipHampirHabisDanExpired();

        return redirect()->route('notifikasi.index')
            ->with('success', "Pengecekan selesai. {$count} status/notifikasi diperbarui.");
    }

    public function markAsRead(Notifikasi $notifikasi)
    {
        $notifikasi->update(['dibaca' => true]);

        return back()->with('success', 'Notifikasi telah ditandai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        Notifikasi::where('dibaca', false)->update(['dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Logic pengganti sp_cek_membership_hampir_habis()
     */
    public function cekMembershipHampirHabisDanExpired(): int
    {
        $today = Carbon::today();
        $warningLimit = Carbon::today()->addDays(7);
        $changesCount = 0;

        // 1. Update membership yang sudah expired
        $expiredMemberships = Membership::where('status', 'aktif')
            ->where('tanggal_berakhir', '<', $today)
            ->get();

        foreach ($expiredMemberships as $ms) {
            $ms->update(['status' => 'expired']);

            // Cek apakah member masih punya membership aktif lainnya
            $hasOtherActive = Membership::where('member_id', $ms->member_id)
                ->where('status', 'aktif')
                ->where('tanggal_berakhir', '>=', $today)
                ->exists();

            if (!$hasOtherActive) {
                Member::where('id', $ms->member_id)->update(['status' => 'expired']);
            }

            // Buat notifikasi expired jika belum ada untuk membership ini
            $exists = Notifikasi::where('membership_id', $ms->id)
                ->where('tipe', 'danger')
                ->exists();

            if (!$exists && $ms->member) {
                Notifikasi::create([
                    'member_id' => $ms->member_id,
                    'membership_id' => $ms->id,
                    'judul' => 'Membership Telah Berakhir',
                    'pesan' => "Membership member {$ms->member->nama} ({$ms->member->kode_member}) telah berakhir pada " . Carbon::parse($ms->tanggal_berakhir)->format('d M Y') . '.',
                    'tipe' => 'danger',
                    'dibaca' => false,
                ]);
                $changesCount++;
            }
        }

        // 2. Cek membership yang hampir habis (1 - 7 hari ke depan)
        $almostExpired = Membership::with('member')
            ->where('status', 'aktif')
            ->whereBetween('tanggal_berakhir', [$today, $warningLimit])
            ->get();

        foreach ($almostExpired as $ms) {
            $daysLeft = $today->diffInDays(Carbon::parse($ms->tanggal_berakhir), false);
            
            // Cek apakah notifikasi warning untuk membership ini sudah dibuat dalam 3 hari terakhir
            $recentNotif = Notifikasi::where('membership_id', $ms->id)
                ->where('tipe', 'warning')
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->exists();

            if (!$recentNotif && $ms->member) {
                $pesanHari = $daysLeft == 0 ? "berakhir HARI INI" : "tersisa {$daysLeft} hari lagi (berakhir " . Carbon::parse($ms->tanggal_berakhir)->format('d M Y') . ")";
                Notifikasi::create([
                    'member_id' => $ms->member_id,
                    'membership_id' => $ms->id,
                    'judul' => 'Masa Aktif Hampir Berakhir',
                    'pesan' => "Membership member {$ms->member->nama} ({$ms->member->kode_member}) {$pesanHari}. Segera lakukan perpanjangan.",
                    'tipe' => 'warning',
                    'dibaca' => false,
                ]);
                $changesCount++;
            }
        }

        return $changesCount;
    }
}
