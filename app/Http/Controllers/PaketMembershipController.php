<?php

namespace App\Http\Controllers;

use App\Models\PaketMembership;
use Illuminate\Http\Request;

class PaketMembershipController extends Controller
{
    public function index()
    {
        $pakets = PaketMembership::withCount('memberships')->latest()->paginate(10);

        return view('paket_memberships.index', compact('pakets'));
    }

    public function create()
    {
        return view('paket_memberships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        PaketMembership::create($validated);

        return redirect()->route('paket-memberships.index')
            ->with('success', "Paket {$validated['nama_paket']} berhasil ditambahkan.");
    }

    public function edit(PaketMembership $paket_membership)
    {
        return view('paket_memberships.edit', compact('paket_membership'));
    }

    public function update(Request $request, PaketMembership $paket_membership)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'durasi_hari' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $paket_membership->update($validated);

        return redirect()->route('paket-memberships.index')
            ->with('success', "Paket {$paket_membership->nama_paket} berhasil diperbarui.");
    }

    public function destroy(PaketMembership $paket_membership)
    {
        $nama = $paket_membership->nama_paket;
        $paket_membership->delete();

        return redirect()->route('paket-memberships.index')
            ->with('success', "Paket {$nama} berhasil dihapus.");
    }
}
