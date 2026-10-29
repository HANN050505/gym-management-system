@extends('layouts.app')

@section('title', 'Data Member')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Member Gym</h3>
        <p class="text-muted mb-0">Kelola informasi anggota, paket aktif, dan kartu QR code absensi.</p>
    </div>
    <a href="{{ route('members.create') }}" class="btn btn-primary fw-medium">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Member Baru
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('members.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, kode member, no hp..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Kode Member</th>
                        <th>Foto & Nama Member</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Paket Aktif</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td class="ps-3">
                            <span class="badge bg-secondary font-monospace">{{ $member->kode_member }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle overflow-hidden bg-light border d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                                    @if($member->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->foto))
                                        <img src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="bi bi-person text-secondary fs-5"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $member->nama }}</div>
                                    <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>{{ $member->no_hp }}</small>
                                    @if($member->email)
                                        <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i>{{ $member->email }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <small class="text-secondary">{{ $member->alamat ?? '-' }}</small>
                        </td>
                        <td>
                            @if($member->status === 'aktif')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                    <i class="bi bi-check-circle me-1"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                    <i class="bi bi-x-circle me-1"></i> Expired
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($member->activeMembership)
                                <div class="small fw-semibold text-primary">{{ $member->activeMembership->paket->nama_paket ?? 'Membership' }}</div>
                                <small class="text-muted d-block">s/d {{ \Carbon\Carbon::parse($member->activeMembership->tanggal_berakhir)->format('d M Y') }}</small>
                            @else
                                <span class="text-muted small">Tidak ada paket aktif</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('members.kartu', $member->id) }}" class="btn btn-outline-dark" title="Cetak Kartu Member (ID & Barcode)" target="_blank">
                                    <i class="bi bi-person-badge"></i>
                                </a>
                                <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-info" title="Lihat Profil & QR">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('members.edit', $member->id) }}" class="btn btn-outline-warning" title="Edit Member">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus member {{ $member->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus Member">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                            Tidak ada data member yang sesuai dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($members->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $members->links() }}
    </div>
    @endif
</div>
@endsection
