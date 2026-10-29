<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }} - DOEL GYM</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 2rem 1rem;
        }

        .receipt-container {
            max-width: 440px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .receipt-header {
            background: #0f172a;
            color: #ffffff;
            padding: 1.5rem;
            text-align: center;
        }

        .receipt-body {
            padding: 1.5rem;
            font-size: 0.92rem;
        }

        .receipt-mono {
            font-family: 'Space Mono', monospace;
        }

        .receipt-divider {
            border-top: 1px dashed #cbd5e1;
            margin: 1rem 0;
        }

        .receipt-total-row {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin: 1rem 0;
            border: 1px solid #e2e8f0;
        }

        @media print {
            body {
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                max-width: 100% !important;
                width: 80mm !important;
                margin: 0 auto !important;
                border-radius: 0 !important;
            }

            .receipt-header {
                background: none !important;
                color: #000000 !important;
                padding: 0.5rem 0 !important;
            }

            .receipt-header * {
                color: #000000 !important;
            }

            .receipt-body {
                padding: 0.5rem 0 !important;
            }

            .receipt-total-row {
                background: none !important;
                border: 1px solid #000000 !important;
            }

            @page {
                size: auto;
                margin: 4mm;
            }
        }
    </style>
</head>
<body>

    <!-- Controls (Screen Only) -->
    <div class="text-center mb-4 no-print" style="max-width: 440px; margin: 0 auto 1.5rem auto;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <a href="{{ route('transaksi-membership.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Riwayat Transaksi
            </a>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold shadow-sm">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Struk Kasir
                </button>
            </div>
        </div>

        @if($transaksi->member && $transaksi->member->no_hp)
            @php
                $waText = "Halo " . $transaksi->member->nama . ", terima kasih telah bertransaksi di DOEL GYM!%0A%0A"
                    . "No. Transaksi: TRX-" . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) . "%0A"
                    . "Paket: " . ($transaksi->paket->nama_paket ?? 'Membership') . "%0A"
                    . "Total: Rp " . number_format($transaksi->total, 0, ',', '.') . "%0A"
                    . "Metode: " . strtoupper($transaksi->metode_pembayaran) . " (LUNAS)%0A"
                    . "Status: AKTIF%0A%0ASelamat berlatih di DOEL GYM!";
                $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $transaksi->member->no_hp));
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                <i class="bi bi-whatsapp me-1"></i> Kirim Struk Pembayaran ke WhatsApp Member
            </a>
        @endif
    </div>

    <!-- Printable Receipt -->
    <div class="receipt-container">
        <!-- Header -->
        <div class="receipt-header">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning text-dark rounded-circle mb-2" style="width: 42px; height: 42px;">
                <i class="bi bi-lightning-charge-fill fs-4"></i>
            </div>
            <h4 class="fw-bold mb-0 text-white">DOEL GYM</h4>
            <div class="small text-white-50">FITNESS & HEALTH CENTER</div>
            <div class="small text-white-50 mt-1" style="font-size: 0.8rem;">
                Jl. Pemuda No. 88 &bull; Telp / WA: 0812-3456-7890
            </div>
        </div>

        <!-- Body -->
        <div class="receipt-body">
            <div class="text-center mb-3">
                <span class="badge bg-dark px-3 py-1 text-uppercase letter-spacing-1">STRUK PEMBAYARAN RESMI</span>
            </div>

            <div class="receipt-mono small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">No. Struk:</span>
                    <span class="fw-bold">#TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Tanggal:</span>
                    <span>{{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d/m/Y') }} {{ $transaksi->created_at->format('H:i') }} WIB</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Kasir / Admin:</span>
                    <span>{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>
            </div>

            <div class="receipt-divider"></div>

            <!-- Member Info -->
            <div class="mb-3">
                <div class="text-muted small mb-1 fw-semibold text-uppercase">Data Member:</div>
                <div class="fw-bold fs-6">{{ $transaksi->member->nama ?? 'Member' }}</div>
                <div class="receipt-mono text-muted small">Kode: {{ $transaksi->member->kode_member ?? '-' }}</div>
                <div class="text-muted small">No. HP: {{ $transaksi->member->no_hp ?? '-' }}</div>
            </div>

            <div class="receipt-divider"></div>

            <!-- Order Items -->
            <div class="mb-3">
                <div class="text-muted small mb-2 fw-semibold text-uppercase">Rincian Paket Membership:</div>
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <div class="fw-bold">{{ $transaksi->paket->nama_paket ?? 'Paket Gym' }}</div>
                        <small class="text-muted">Durasi {{ $transaksi->paket->durasi_hari ?? '-' }} Hari</small>
                    </div>
                    <div class="fw-bold receipt-mono">
                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                    </div>
                </div>

                @if($transaksi->membership)
                <div class="p-2 bg-light rounded mt-2 small">
                    <div class="text-muted" style="font-size: 0.78rem;">Periode Masa Aktif Baru:</div>
                    <div class="fw-semibold text-primary">
                        {{ \Carbon\Carbon::parse($transaksi->membership->tanggal_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($transaksi->membership->tanggal_berakhir)->format('d M Y') }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Total Box -->
            <div class="receipt-total-row">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted text-uppercase fw-semibold">Total Pembayaran</div>
                        <div class="small text-success fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> STATUS: LUNAS
                        </div>
                    </div>
                    <div class="fs-4 fw-bold text-dark receipt-mono">
                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="receipt-mono small">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Metode Bayar:</span>
                    <span class="fw-bold text-uppercase">{{ $transaksi->metode_pembayaran }}</span>
                </div>
                @if($transaksi->catatan)
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Catatan:</span>
                    <span>{{ $transaksi->catatan }}</span>
                </div>
                @endif
            </div>

            <div class="receipt-divider"></div>

            <!-- Barcode & Footer -->
            <div class="text-center">
                <div class="mb-2 d-flex justify-content-center" style="max-height: 40px; overflow: hidden;">
                    {!! \App\Services\BarcodeService::generateSvg('TRX-' . str_pad($transaksi->id, 5, '0', STR_PAD_LEFT), 32, 2) !!}
                </div>
                <div class="small text-muted font-monospace mb-3">TRX-{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</div>

                <p class="small text-muted mb-1 fst-italic">
                    "Terima kasih telah bergabung di DOEL GYM.<br>Simpan struk ini sebagai bukti pembayaran yang sah."
                </p>
                <div class="text-muted" style="font-size: 0.72rem;">
                    Dicetak otomatis oleh Sistem DOEL GYM
                </div>
            </div>
        </div>
    </div>

</body>
</html>

