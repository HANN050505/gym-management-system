@extends('layouts.app')

@section('title', 'Scan Absensi QR & Barcode')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Scan Absensi QR & Barcode Member</h3>
        <p class="text-muted mb-0">Arahkan kartu QR code / Barcode member ke pemindai atau ketik kode member secara manual.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('absensi.riwayat') }}" class="btn btn-outline-primary">
            <i class="bi bi-clock-history me-1"></i> Lihat Semua Riwayat
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- QR Scanner Section -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-camera me-1 text-primary"></i> Pemindai Kamera (QR & Barcode)</span>
                <span class="badge bg-success bg-opacity-10 text-success border border-success">
                    <i class="bi bi-broadcast me-1"></i> Kamera Siap
                </span>
            </div>
            <div class="card-body text-center p-3">
                <div id="reader" style="width: 100%; max-width: 450px; margin: 0 auto; border-radius: 8px; overflow: hidden; background: #000;"></div>
                <div class="text-muted small mt-2">
                    <i class="bi bi-info-circle me-1"></i> Kamera otomatis memproses begitu QR / Barcode member terdeteksi.
                </div>
            </div>
        </div>
    </div>

    <!-- Manual / Barcode Gun Input & Today's Attendance -->
    <div class="col-lg-6">
        <!-- Manual / Barcode Gun Input Card -->
        <div class="card shadow-sm mb-4 border-primary">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-upc-scan me-1 text-primary"></i> Scan Barcode Gun / Input Kode</span>
                <span class="badge bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-lightning-fill me-1"></i> Auto-Focus Scanner
                </span>
            </div>
            <div class="card-body">
                <form id="absensiForm" method="POST" action="{{ route('absensi.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label fw-semibold">Kode Member / Scan Barcode</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white"><i class="bi bi-upc-scan text-primary"></i></span>
                            <input type="text" id="kode_member" name="kode_member" class="form-control font-monospace text-uppercase fw-bold" placeholder="Contoh: MBR-2026-0001" autofocus required autocomplete="off">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Check-in
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted small mt-2">
                        <span><i class="bi bi-keyboard me-1"></i> Gunakan Barcode Scanner Gun (USB) atau ketik kode lalu tekan Enter.</span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Today's Attendance List -->
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-check2-circle me-1 text-success"></i> Check-in Hari Ini ({{ date('d M Y') }})</span>
                <span class="badge bg-success fs-6">{{ $todayCount }} Member Hadir</span>
            </div>
            <div class="card-body p-0">
                @if($todayAbsensis->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada member yang melakukan absensi hari ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Jam Masuk</th>
                                    <th>Member</th>
                                    <th>Kode</th>
                                    <th class="text-end pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAbsensis as $absen)
                                <tr>
                                    <td class="ps-3 fw-bold text-primary">
                                        <i class="bi bi-clock me-1"></i>{{ substr($absen->jam, 0, 5) }} WIB
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle overflow-hidden bg-light border d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; flex-shrink: 0;">
                                                @if($absen->member && $absen->member->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($absen->member->foto))
                                                    <img src="{{ asset('storage/' . $absen->member->foto) }}" alt="{{ $absen->member->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <i class="bi bi-person text-secondary small"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $absen->member->nama ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $absen->member->no_hp ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $absen->member->kode_member ?? '-' }}</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                            <i class="bi bi-check-circle me-1"></i> Hadir
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    // Audio Feedback using Web Audio API Synthesizer
    function playBeep(type = 'success') {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const now = ctx.currentTime;

            if (type === 'success') {
                // Cheerful 2-tone chime (high ding-ding)
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, now); // D5
                osc1.frequency.setValueAtTime(880, now + 0.1); // A5
                gain1.gain.setValueAtTime(0.3, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(now);
                osc1.stop(now + 0.4);
            } else {
                // Warning / Error buzz (low tone)
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, now); // A3
                osc.frequency.setValueAtTime(160, now + 0.15);
                gain.gain.setValueAtTime(0.3, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.45);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.45);
            }
        } catch (e) {
            console.log("Audio not supported / user interaction required:", e);
        }
    }

    // Check if flash message exists and trigger audio feedback
    @if(session('success'))
        playBeep('success');
    @elseif(session('error') || session('warning'))
        playBeep('error');
    @endif

    // Continuous auto-focus for barcode scanner guns
    const inputField = document.getElementById('kode_member');
    if (inputField) {
        inputField.focus();
        document.addEventListener('click', function(e) {
            // If click was outside form inputs, refocus scanner input
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
                inputField.focus();
            }
        });
    }

    // Camera QR Scanner
    let isSubmitting = false;

    function onScanSuccess(decodedText) {
        if (isSubmitting) return;

        const input = document.getElementById('kode_member');
        const form = document.getElementById('absensiForm');

        if (input && form) {
            isSubmitting = true;
            input.value = decodedText.trim();
            form.submit();
        }
    }

    try {
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                rememberLastUsedCamera: true
            }
        );
        html5QrcodeScanner.render(onScanSuccess);
    } catch (e) {
        console.error("Camera init error:", e);
    }
</script>
@endpush
