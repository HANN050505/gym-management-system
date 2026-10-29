<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        $todayAbsensis = Absensi::with('member')
            ->whereDate('tanggal', $today)
            ->latest('jam')
            ->take(10)
            ->get();

        $todayCount = Absensi::whereDate('tanggal', $today)->count();

        return view('absensi.index', compact('todayAbsensis', 'todayCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_member' => 'required|string',
        ]);

        $kodeMember = trim($request->kode_member);

        $member = Member::where('kode_member', $kodeMember)->first();

        if (!$member) {
            return back()->with('error', "Member dengan kode '{$kodeMember}' tidak ditemukan.");
        }

        if ($member->status !== 'aktif') {
            return back()->with('error', "Membership untuk {$member->nama} sudah EXPIRED / tidak aktif. Harap perpanjang membership terlebih dahulu.");
        }

        $today = Carbon::today()->toDateString();

        // Cek apakah sudah absen hari ini
        $alreadyCheckedIn = Absensi::where('member_id', $member->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($alreadyCheckedIn) {
            return back()->with('warning', "Member {$member->nama} sudah melakukan absensi hari ini pada jam {$alreadyCheckedIn->jam}.");
        }

        Absensi::create([
            'member_id' => $member->id,
            'tanggal' => $today,
            'jam' => Carbon::now()->format('H:i:s'),
        ]);

        return back()->with('success', "Absensi berhasil dicatat untuk {$member->nama} ({$member->kode_member}) pada " . Carbon::now()->format('H:i') . " WIB.");
    }

    public function riwayat(Request $request)
    {
        $query = Absensi::with('member')->latest('tanggal')->latest('jam');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%");
            });
        }

        $absensis = $query->paginate(15)->withQueryString();

        $totalCheckins = Absensi::count();
        $todayCheckins = Absensi::whereDate('tanggal', Carbon::today())->count();

        return view('absensi.riwayat', compact('absensis', 'totalCheckins', 'todayCheckins'));
    }
}
