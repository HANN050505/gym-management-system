@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold mb-0">Edit Data Member</h3>
                <small class="text-muted">Perbarui data member {{ $member->nama }} ({{ $member->kode_member }})</small>
            </div>
            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Foto Member Upload -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-semibold d-block">Foto Member (Kartu Identitas)</label>
                        <div class="d-inline-block position-relative">
                            <div id="photoPreviewContainer" class="rounded-3 border border-2 border-dashed bg-light d-flex align-items-center justify-content-center overflow-hidden mx-auto shadow-sm" style="width: 130px; height: 160px; cursor: pointer;" onclick="document.getElementById('fotoInput').click()">
                                @if($member->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->foto))
                                    <img id="photoPreview" src="{{ asset('storage/' . $member->foto) }}" alt="Preview" class="w-100 h-100" style="object-fit: cover;">
                                    <div id="photoPlaceholder" class="d-none text-center text-muted p-2">
                                        <i class="bi bi-camera-fill fs-2 text-secondary"></i>
                                        <div class="small fw-medium mt-1">Ubah Foto</div>
                                    </div>
                                @else
                                    <div id="photoPlaceholder" class="text-center text-muted p-2">
                                        <i class="bi bi-camera-fill fs-2 text-secondary"></i>
                                        <div class="small fw-medium mt-1">Pilih Foto</div>
                                        <small style="font-size: 10px;">Max 2MB</small>
                                    </div>
                                    <img id="photoPreview" src="" alt="Preview" class="d-none w-100 h-100" style="object-fit: cover;">
                                @endif
                            </div>
                            <input type="file" id="fotoInput" name="foto" class="d-none" accept="image/*" onchange="previewMemberPhoto(this)">
                            <small class="text-muted d-block mt-1">Klik gambar untuk mengganti foto</small>
                        </div>
                        @error('foto')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode Member</label>
                            <input type="text" class="form-control bg-light font-monospace" value="{{ $member->kode_member }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status Membership</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="aktif" {{ old('status', $member->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="expired" {{ old('status', $member->status) === 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $member->nama) }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">No. HP / WhatsApp <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $member->no_hp) }}" required>
                            </div>
                            @error('no_hp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Alamat Tempat Tinggal</label>
                        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $member->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('members.index') }}" class="btn btn-light border">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Perbarui Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewMemberPhoto(input) {
        const preview = document.getElementById('photoPreview');
        const placeholder = document.getElementById('photoPlaceholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
