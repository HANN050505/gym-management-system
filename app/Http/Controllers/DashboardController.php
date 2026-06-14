<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Absensi;
use App\Models\TransaksiMembership;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMember = Member::count();

        $memberAktif = Member::where(
            'status',
            'aktif'
        )->count();

        $memberExpired = Member::where(
            'status',
            'expired'
        )->count();

        $absensiHariIni = Absensi::whereDate(
            'tanggal',
            now()->toDateString()
        )->count();

        $totalTransaksi =
            TransaksiMembership::count();

        return view(
            'dashboard.index',
            compact(
                'totalMember',
                'memberAktif',
                'memberExpired',
                'absensiHariIni',
                'totalTransaksi'
            )
        );
    }
}