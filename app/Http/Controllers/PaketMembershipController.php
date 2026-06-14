<?php

namespace App\Http\Controllers;

use App\Models\PaketMembership;
use Illuminate\Http\Request;

class PaketMembershipController extends Controller
{
    public function index()
    {
        $pakets = PaketMembership::all();

        return view(
            'paket_memberships.index',
            compact('pakets')
        );
    }

    public function create()
    {
        return view('paket_memberships.create');
    }

    public function store(Request $request)
    {
        PaketMembership::create([
            'nama_paket' => $request->nama_paket,
            'durasi_hari' => $request->durasi_hari,
            'harga' => $request->harga
        ]);

        return redirect()
            ->route('paket-memberships.index');
    }

    public function edit(PaketMembership $paket_membership)
    {
        return view(
            'paket_memberships.edit',
            compact('paket_membership')
        );
    }

    public function update(
        Request $request,
        PaketMembership $paket_membership
    )
    {
        $paket_membership->update([
            'nama_paket' => $request->nama_paket,
            'durasi_hari' => $request->durasi_hari,
            'harga' => $request->harga
        ]);

        return redirect()
            ->route('paket-memberships.index');
    }

    public function destroy(
        PaketMembership $paket_membership
    )
    {
        $paket_membership->delete();

        return redirect()
            ->route('paket-memberships.index');
    }
}