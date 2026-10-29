<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Membership;
use App\Models\PaketMembership;
use App\Models\TransaksiMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiMembershipController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::orderBy('nama')->get();
        $pakets = PaketMembership::where('status', 'aktif')->orderBy('nama_paket')->get();

        $query = TransaksiMembership::with(['member', 'paket', 'membership'])->latest('tanggal_bayar');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_member', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_bayar', [$request->start_date, $request->end_date]);
        }

        $transaksis = $query->paginate(10)->withQueryString();

        return view('transaksi_membership.index', compact('members', 'pakets', 'transaksis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'paket_id' => 'required|exists:paket_memberships,id',
            'metode_pembayaran' => 'required|in:cash,transfer,qris',
            'catatan' => 'nullable|string|max:255',
        ]);

        $transaksiBaru = null;

        DB::transaction(function () use ($validated, &$transaksiBaru) {
            $member = Member::findOrFail($validated['member_id']);
            $paket = PaketMembership::findOrFail($validated['paket_id']);

            // Cek apakah ada membership aktif saat ini
            $activeMembership = Membership::where('member_id', $member->id)
                ->where('status', 'aktif')
                ->where('tanggal_berakhir', '>=', Carbon::today())
                ->latest('tanggal_berakhir')
                ->first();

            $startDate = Carbon::today();
            if ($activeMembership) {
                // Perpanjang dari tanggal berakhir sebelumnya
                $startDate = Carbon::parse($activeMembership->tanggal_berakhir)->addDay();
            }

            $endDate = $startDate->copy()->addDays($paket->durasi_hari);

            // Buat membership baru
            $membership = Membership::create([
                'member_id' => $member->id,
                'paket_membership_id' => $paket->id,
                'tanggal_mulai' => $startDate,
                'tanggal_berakhir' => $endDate,
                'status' => 'aktif',
            ]);

            // Update status member jadi aktif
            $member->update(['status' => 'aktif']);

            // Simpan transaksi pembayaran
            $transaksiBaru = TransaksiMembership::create([
                'member_id' => $member->id,
                'paket_membership_id' => $paket->id,
                'membership_id' => $membership->id,
                'total' => $paket->harga,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => 'lunas',
                'catatan' => $validated['catatan'] ?? null,
                'tanggal_bayar' => Carbon::today(),
            ]);
        });

        return redirect()->route('transaksi-membership.index')
            ->with('success', 'Transaksi berhasil disimpan dan paket membership aktif.')
            ->with('last_transaksi_id', $transaksiBaru?->id);
    }

    public function struk(TransaksiMembership $transaksi)
    {
        $transaksi->load(['member', 'paket', 'membership']);

        return view('transaksi_membership.struk', compact('transaksi'));
    }
}
