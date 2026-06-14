<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function member()
    {
        $laporan = DB::table('v_laporan_member')->get();

        return view(
            'laporan.member',
            compact('laporan')
        );
    }
}