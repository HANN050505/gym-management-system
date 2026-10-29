@extends('layouts.app')

@section('title', 'Peringatan & Notifikasi Membership')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Peringatan Masa Aktif Membership</h3>
        <p class="text-muted mb-0">Daftar anggota yang masa berlakunya segera berakhir atau telah expired.</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('notifikasi.generate') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary fw-medium">
                <i class="bi bi-arrow-repeat me-1"></i> Periksa Status Sekarang
            </button>
        </form>
        @if($unreadCount > 0)
        <form action="{{ route('notifikasi.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary fw-medium">
                <i class="bi bi-check2-all me-1"></i> Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-bell me-1 text-primary"></i> Daftar Pemberitahuan</span>
        <span class="badge bg-warning text-dark">{{ $unreadCount }} Belum Dibaca</span>
    </div>
    <div class="card-body p-0">
        @if($notifikasis->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-2 text-secondary opacity-50"></i>
                Tidak ada notifikasi kedaluwarsa membership saat ini.
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach($notifikasis as $n)
                <div class="list-group-item p-3 {{ !$n->dibaca ? 'bg-light border-start border-warning border-4' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-3 align-items-start">
                            <div class="mt-1">
                                @if($n->tipe === 'danger')
                                    <span class="badge bg-danger p-2 rounded-circle"><i class="bi bi-exclamation-triangle"></i></span>
                                @else
                                    <span class="badge bg-warning text-dark p-2 rounded-circle"><i class="bi bi-clock-history"></i></span>
                                @endif
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="fw-bold mb-0 {{ $n->tipe === 'danger' ? 'text-danger' : 'text-dark' }}">{{ $n->judul }}</h6>
                                    @if(!$n->dibaca)
                                        <span class="badge bg-warning text-dark small">Baru</span>
                                    @endif
                                </div>
                                <p class="text-secondary mb-2">{{ $n->pesan }}</p>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span><i class="bi bi-person me-1"></i>{{ $n->member->nama ?? 'Member' }} ({{ $n->member->kode_member ?? '-' }})</span>
                                    <span><i class="bi bi-calendar me-1"></i>{{ $n->created_at->diffForHumans() }}</span>
                                    @if($n->member && $n->member->no_hp)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $n->member->no_hp)) }}?text=Halo%20{{ urlencode($n->member->nama) }},%20mengingatkan%20membership%20gym%20Anda%20segera%20berakhir.%20Terima%20kasih." target="_blank" class="text-success text-decoration-none fw-medium">
                                            <i class="bi bi-whatsapp me-1"></i> Hubungi Member via WA
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if(!$n->dibaca)
                            <form action="{{ route('notifikasi.markAsRead', $n->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-secondary btn-sm" title="Tandai Sudah Dibaca">
                                    <i class="bi bi-check2"></i>
                                </button>
                            </form>
                            @endif
                            <a href="{{ route('transaksi-membership.index') }}" class="btn btn-outline-primary btn-sm" title="Perpanjang Membership">
                                <i class="bi bi-credit-card me-1"></i> Perpanjang
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @if($notifikasis->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $notifikasis->links() }}
    </div>
    @endif
</div>
@endsection
