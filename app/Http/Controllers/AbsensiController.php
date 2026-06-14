<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Member;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('absensi.index');
    }
    

    public function store(Request $request)
    {
        $member = Member::where(
            'kode_member',
            $request->kode_member
        )->first();

        if (!$member) {

            return back()->with(
                'error',
                'Member tidak ditemukan'
            );

        }
        if ($member->status != 'aktif')
        {
             return back()->with(
                'error',
                 'Membership tidak aktif'
             );
        }       

        Absensi::create([
            'member_id' => $member->id,
            'tanggal' => now()->toDateString(),
            'jam' => now()->format('H:i:s')
        ]);

        return back()->with(
            'success',
            'Absensi berhasil untuk ' .
            $member->nama
        );

        
    }

    public function riwayat()
{
    $absensis = \App\Models\Absensi::with('member')
                    ->latest()
                    ->get();

    return view(
        'absensi.riwayat',
        compact('absensis')
    );
}
}