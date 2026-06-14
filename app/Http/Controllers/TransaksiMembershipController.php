<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PaketMembership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiMembershipController extends Controller
{
    public function index()
    {
        $members = Member::all();
        $pakets = PaketMembership::all();

        return view(
            'transaksi_membership.index',
            compact('members','pakets')
        );
    }

    public function store(Request $request)
    {
        DB::statement(
            'CALL sp_beli_membership(?, ?)',
            [
                $request->member_id,
                $request->paket_id
            ]
        );

        return back()
            ->with(
                'success',
                'Membership berhasil dibuat'
            );
    }
}