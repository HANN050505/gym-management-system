<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['activeMembership.paket'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->status, ['aktif', 'expired'])) {
            $query->where('status', $request->status);
        }

        $members = $query->paginate(10)->withQueryString();

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $nextId = (Member::max('id') ?? 0) + 1;
        $kodeMember = 'MBR-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Ensure unique
        while (Member::where('kode_member', $kodeMember)->exists()) {
            $nextId++;
            $kodeMember = 'MBR-' . date('Y') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('members', 'public');
        }

        Member::create([
            'kode_member' => $kodeMember,
            'nama' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'] ?? null,
            'foto' => $fotoPath,
            'status' => 'expired', // default sampai beli paket membership
        ]);

        return redirect()->route('members.index')
            ->with('success', "Member {$validated['nama']} berhasil didaftarkan dengan kode {$kodeMember}.");
    }

    public function show(Member $member)
    {
        $member->load([
            'memberships.paket',
            'activeMembership.paket',
            'absensis' => function ($q) {
                $q->latest()->take(10);
            },
            'transaksiMemberships.paket'
        ]);

        return view('members.show', compact('member'));
    }

    public function kartu(Member $member)
    {
        $member->load([
            'activeMembership.paket',
            'memberships.paket',
        ]);

        return view('members.card', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string|max:500',
            'status' => 'nullable|in:aktif,expired',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $fotoPath = $member->foto;
        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($member->foto && Storage::disk('public')->exists($member->foto)) {
                Storage::disk('public')->delete($member->foto);
            }
            $fotoPath = $request->file('foto')->store('members', 'public');
        }

        $member->update([
            'nama' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'] ?? null,
            'status' => $validated['status'] ?? $member->status,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('members.index')
            ->with('success', "Data member {$member->nama} berhasil diperbarui.");
    }

    public function destroy(Member $member)
    {
        $nama = $member->nama;
        if ($member->foto && Storage::disk('public')->exists($member->foto)) {
            Storage::disk('public')->delete($member->foto);
        }
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', "Member {$nama} berhasil dihapus.");
    }
}
