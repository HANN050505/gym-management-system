<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    public function index()
    {
        DB::statement(
            'CALL sp_cek_membership_hampir_habis()'
        );

        $notifikasis = Notifikasi::latest()->get();

        return view(
            'notifikasi.index',
            compact('notifikasis')
        );
    }
}