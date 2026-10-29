<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Absensi;
use App\Models\Membership;
use App\Models\Notifikasi;
use App\Models\TransaksiMembership;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $totalMember = Member::count();
        $memberAktif = Member::where('status', 'aktif')->count();
        $memberExpired = Member::where('status', 'expired')->count();
        $absensiHariIni = Absensi::whereDate('tanggal', $today)->count();
        $totalTransaksi = TransaksiMembership::count();
        $totalPendapatan = TransaksiMembership::sum('total');
        $pendapatanBulanIni = TransaksiMembership::whereMonth('tanggal_bayar', Carbon::now()->month)
            ->whereYear('tanggal_bayar', Carbon::now()->year)
            ->sum('total');

        $transaksiTerbaru = TransaksiMembership::with(['member', 'paket'])
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        $absensiTerbaru = Absensi::with('member')
            ->whereDate('tanggal', $today)
            ->latest('jam')
            ->take(5)
            ->get();

        $unreadNotifCount = Notifikasi::where('dibaca', false)->count();

        return view('dashboard.index', compact(
            'totalMember',
            'memberAktif',
            'memberExpired',
            'absensiHariIni',
            'totalTransaksi',
            'totalPendapatan',
            'pendapatanBulanIni',
            'transaksiTerbaru',
            'absensiTerbaru',
            'unreadNotifCount'
        ));
    }
}
