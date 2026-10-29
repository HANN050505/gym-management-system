<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\TransaksiMembership;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function member(Request $request)
    {
        $query = Member::with([
            'memberships' => function ($q) {
                $q->latest('tanggal_berakhir')->with('paket');
            },
            'absensis'
        ]);

        if ($request->filled('status') && in_array($request->status, ['aktif', 'expired'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(15)->withQueryString();

        // Statistik summary
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'aktif')->count();
        $expiredMembers = Member::where('status', 'expired')->count();
        $totalRevenue = TransaksiMembership::sum('total');
        $totalAbsensi = Absensi::count();

        return view('laporan.member', compact(
            'members',
            'totalMembers',
            'activeMembers',
            'expiredMembers',
            'totalRevenue',
            'totalAbsensi'
        ));
    }
}
