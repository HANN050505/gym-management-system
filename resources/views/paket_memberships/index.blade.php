@extends('layouts.app')

@section('title', 'Paket Membership')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Paket Membership</h3>
        <p class="text-muted mb-0">Kelola daftar pilihan durasi dan tarif keanggotaan gym.</p>
    </div>
    <a href="{{ route('paket-memberships.create') }}" class="btn btn-primary fw-medium">
        <i class="bi bi-plus-circle me-1"></i> Tambah Paket Baru
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Nama Paket</th>
                        <th>Durasi</th>
                        <th>Tarif / Harga</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $paket)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold fs-6">{{ $paket->nama_paket }}</div>
                            <small class="text-muted">{{ $paket->memberships_count ?? 0 }} total langganan</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                <i class="bi bi-calendar-range me-1 text-primary"></i> {{ $paket->durasi_hari }} Hari
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-success fs-6">
                                Rp {{ number_format($paket->harga, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $paket->deskripsi ?? '-' }}</small>
                        </td>
                        <td>
                            @if(($paket->status ?? 'aktif') === 'aktif')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('paket-memberships.edit', $paket->id) }}" class="btn btn-outline-warning" title="Edit Paket">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('paket-memberships.destroy', $paket->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket {{ $paket->nama_paket }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus Paket">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            Belum ada paket membership yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($pakets->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $pakets->links() }}
    </div>
    @endif
</div>
@endsection
