<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Member - {{ $member->nama }} ({{ $member->kode_member }}) - DOEL GYM</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">

    <style>
        :root {
            --card-width: 85.6mm;
            --card-height: 53.98mm;
            --card-radius: 3.5mm;
            --gold-accent: #f59e0b;
            --gold-gradient: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding: 0;
        }

        .screen-toolbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-preview-area {
            min-height: calc(100vh - 85px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
        }

        /* Container for cards */
        .cards-wrapper {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        /* Physical Card Dimensions (CR-80 / PVC standard) */
        .gym-card {
            width: var(--card-width);
            height: var(--card-height);
            border-radius: var(--card-radius);
            position: relative;
            overflow: hidden;
            box-sizing: border-box;
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.25), 0 8px 10px -6px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: #0f172a;
            color: #ffffff;
            user-select: none;
            page-break-inside: avoid;
            flex-shrink: 0;
        }

        /* FRONT CARD STYLES */
        .gym-card-front {
            background: radial-gradient(circle at 90% 10%, #1e293b 0%, #0f172a 60%, #020617 100%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            padding: 3.5mm 4.5mm;
        }

        /* Metallic gold accents */
        .card-gold-line {
            height: 2px;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, transparent);
            margin: 2mm 0;
        }

        .card-header-brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo-area {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .brand-logo-icon {
            width: 7.5mm;
            height: 7.5mm;
            border-radius: 1.8mm;
            background: var(--gold-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            font-size: 4.5mm;
            font-weight: 800;
            box-shadow: 0 2px 5px rgba(245, 158, 11, 0.4);
        }

        .brand-name {
            font-size: 4.2mm;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #ffffff;
            line-height: 1.1;
        }

        .brand-subtitle {
            font-size: 1.8mm;
            color: #f59e0b;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .chip-nfc-area {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .sim-chip {
            width: 7mm;
            height: 5.5mm;
            background: linear-gradient(135deg, #fcd34d 0%, #b45309 100%);
            border-radius: 1mm;
            border: 0.5px solid rgba(255, 255, 255, 0.4);
            position: relative;
        }
        .sim-chip::after {
            content: '';
            position: absolute;
            top: 25%;
            left: 0;
            right: 0;
            height: 50%;
            border-top: 0.5px solid rgba(0, 0, 0, 0.3);
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.3);
        }

        .nfc-icon {
            color: rgba(255, 255, 255, 0.6);
            font-size: 3.5mm;
        }

        /* Middle content: Photo + Details */
        .card-body-content {
            display: flex;
            gap: 3.5mm;
            align-items: center;
            margin-top: 1mm;
            margin-bottom: 1mm;
        }

        .member-photo-frame {
            width: 17mm;
            height: 21mm;
            border-radius: 2mm;
            border: 1.5px solid #f59e0b;
            overflow: hidden;
            background: #1e293b;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }

        .member-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .member-photo-placeholder {
            color: #94a3b8;
            font-size: 9mm;
            text-align: center;
        }

        .member-info-col {
            flex-grow: 1;
            overflow: hidden;
        }

        .member-name-text {
            font-size: 3.8mm;
            font-weight: 800;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 0.8mm;
        }

        .member-code-badge {
            font-family: 'Space Mono', monospace;
            font-size: 2.8mm;
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            padding: 0.5mm 1.8mm;
            border-radius: 1mm;
            display: inline-block;
            border: 0.5px solid rgba(245, 158, 11, 0.4);
            margin-bottom: 1.2mm;
            letter-spacing: 0.5px;
        }

        .member-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1mm 2mm;
            font-size: 1.9mm;
        }

        .meta-label {
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-size: 1.6mm;
            margin-bottom: 0.2mm;
        }

        .meta-value {
            color: #e2e8f0;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-pill-active {
            color: #34d399;
            font-weight: 700;
        }

        .status-pill-expired {
            color: #f87171;
            font-weight: 700;
        }

        /* Bottom Row: Barcode + QR Code */
        .card-bottom-barcodes {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2mm;
            background: #ffffff;
            border-radius: 1.8mm;
            padding: 1.2mm 2.2mm;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }

        .barcode-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
        }

        .barcode-svg-container {
            width: 100%;
            height: 6mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .barcode-svg-container svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .barcode-readable-text {
            font-family: 'Space Mono', monospace;
            font-size: 1.8mm;
            letter-spacing: 1.2px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.3mm;
            line-height: 1;
        }

        .qrcode-section {
            width: 8.5mm;
            height: 8.5mm;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 0.3mm;
            border-left: 1px dashed #cbd5e1;
            padding-left: 1.8mm;
        }

        .qrcode-section svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* BACK CARD STYLES */
        .gym-card-back {
            background: #0f172a;
            border: 1px solid rgba(245, 158, 11, 0.35);
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mag-stripe {
            width: 100%;
            height: 8mm;
            background: #020617;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2.5mm;
            position: relative;
        }
        .mag-stripe::after {
            content: 'DOEL GYM VERIFIED ACCESS';
            position: absolute;
            right: 3mm;
            top: 2mm;
            color: rgba(255, 255, 255, 0.15);
            font-size: 1.7mm;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .back-content-area {
            padding: 2mm 4.5mm 3.5mm 4.5mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .terms-box {
            font-size: 1.7mm;
            color: #cbd5e1;
            line-height: 1.35;
        }

        .terms-title {
            font-weight: 700;
            color: #f59e0b;
            font-size: 1.9mm;
            text-transform: uppercase;
            margin-bottom: 0.8mm;
            display: flex;
            align-items: center;
            gap: 1mm;
        }

        .terms-list {
            margin: 0;
            padding-left: 3.5mm;
        }

        .terms-list li {
            margin-bottom: 0.5mm;
        }

        .signature-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 1.5mm;
            gap: 2mm;
        }

        .signature-box {
            flex-grow: 1;
            background: #ffffff;
            height: 6.5mm;
            border-radius: 1mm;
            position: relative;
            display: flex;
            align-items: center;
            padding: 0 2mm;
            color: #475569;
            font-size: 1.6mm;
            font-style: italic;
            border: 0.5px solid #94a3b8;
        }

        .signature-box::after {
            content: 'Authorized Member Signature';
            position: absolute;
            right: 2mm;
            bottom: 0.8mm;
            font-size: 1.3mm;
            color: #94a3b8;
            font-style: normal;
        }

        .gym-contact-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            padding-top: 1mm;
            margin-top: 1mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.7mm;
            color: #94a3b8;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1mm;
        }

        .contact-item i {
            color: #f59e0b;
            font-size: 2mm;
        }

        /* PRINT MEDIA STYLES */
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .screen-toolbar,
            .print-tips-box,
            .no-print {
                display: none !important;
            }

            .card-preview-area {
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }

            .cards-wrapper {
                gap: 5mm !important;
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: flex-start !important;
                page-break-after: avoid;
            }

            .gym-card {
                box-shadow: none !important;
                border: 0.5px solid #000000 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                page-break-inside: avoid !important;
                margin: 0 !important;
            }

            @page {
                size: auto;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (Screen Only) -->
    <header class="screen-toolbar no-print">
        <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('members.show', $member->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Profil Member
                </a>
                <div class="vr"></div>
                <div>
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-person-badge text-warning me-1"></i> Kartu Member DOEL GYM
                    </h5>
                    <small class="text-muted">{{ $member->nama }} &bull; <span class="font-monospace fw-bold text-dark">{{ $member->kode_member }}</span></small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <!-- Mode View Selector -->
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-dark active" id="btnShowBoth" onclick="setViewMode('both')">
                        <i class="bi bi-front me-1"></i> Depan & Belakang
                    </button>
                    <button type="button" class="btn btn-outline-dark" id="btnShowFront" onclick="setViewMode('front')">
                        <i class="bi bi-card-heading me-1"></i> Depan Saja
                    </button>
                    <button type="button" class="btn btn-outline-dark" id="btnShowBack" onclick="setViewMode('back')">
                        <i class="bi bi-card-text me-1"></i> Belakang Saja
                    </button>
                </div>

                <button onclick="window.print()" class="btn btn-primary btn-sm fw-semibold px-3 shadow-sm">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Kartu Sekarang
                </button>
            </div>
        </div>
    </header>

    <!-- Main Card Preview Area -->
    <main class="card-preview-area">
        
        <div class="cards-wrapper" id="cardsWrapper">
            
            <!-- SISI DEPAN KARTU (FRONT SIDE) -->
            <div class="gym-card gym-card-front" id="cardFront">
                <!-- Top Header: Logo + Chip + NFC -->
                <div>
                    <div class="card-header-brand">
                        <div class="brand-logo-area">
                            <div class="brand-logo-icon">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <div class="brand-name">DOEL GYM</div>
                                <div class="brand-subtitle">FITNESS & HEALTH CENTER</div>
                            </div>
                        </div>

                        <div class="chip-nfc-area">
                            <i class="bi bi-wifi nfc-icon" title="Contactless / RFID ready"></i>
                            <div class="sim-chip" title="Smart Card Member"></div>
                        </div>
                    </div>

                    <div class="card-gold-line"></div>
                </div>

                <!-- Middle Content: Foto + Data Identitas Member -->
                <div class="card-body-content">
                    <div class="member-photo-frame">
                        @if($member->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($member->foto))
                            <img src="{{ asset('storage/' . $member->foto) }}" alt="{{ $member->nama }}">
                        @else
                            <div class="member-photo-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                        @endif
                    </div>

                    <div class="member-info-col">
                        <div class="member-name-text" title="{{ $member->nama }}">
                            {{ $member->nama }}
                        </div>
                        <div class="member-code-badge">
                            {{ $member->kode_member }}
                        </div>

                        <div class="member-meta-grid">
                            <div>
                                <div class="meta-label">Paket Gym</div>
                                <div class="meta-value">
                                    {{ $member->activeMembership->paket->nama_paket ?? 'Reguler Pass' }}
                                </div>
                            </div>

                            <div>
                                <div class="meta-label">Status</div>
                                <div class="meta-value">
                                    @if($member->status === 'aktif')
                                        <span class="status-pill-active"><i class="bi bi-check-circle-fill me-1"></i>AKTIF</span>
                                    @else
                                        <span class="status-pill-expired"><i class="bi bi-x-circle-fill me-1"></i>EXPIRED</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="meta-label">Masa Berlaku</div>
                                <div class="meta-value">
                                    @if($member->activeMembership)
                                        {{ \Carbon\Carbon::parse($member->activeMembership->tanggal_berakhir)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            <div>
                                <div class="meta-label">Tipe Akses</div>
                                <div class="meta-value text-warning fw-bold">VIP MEMBER</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Barcode & QR Code Section -->
                <div class="card-bottom-barcodes">
                    <div class="barcode-section">
                        <div class="barcode-svg-container">
                            {!! \App\Services\BarcodeService::generateSvg($member->kode_member, 24, 1) !!}
                        </div>
                        <div class="barcode-readable-text">{{ $member->kode_member }}</div>
                    </div>

                    <div class="qrcode-section">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(50)->margin(0)->generate($member->kode_member) !!}
                    </div>
                </div>
            </div>

            <!-- SISI BELAKANG KARTU (BACK SIDE) -->
            <div class="gym-card gym-card-back" id="cardBack">
                <!-- Magnetic Stripe -->
                <div class="mag-stripe"></div>

                <!-- Back Content -->
                <div class="back-content-area">
                    <div class="terms-box">
                        <div class="terms-title">
                            <i class="bi bi-shield-check"></i> Syarat & Ketentuan Keanggotaan
                        </div>
                        <ol class="terms-list">
                            <li>Kartu ini adalah identitas resmi anggota DOEL GYM dan wajib dibawa/discan saat check-in.</li>
                            <li>Kartu keanggotaan ini bersifat pribadi dan tidak dapat dipindahtangankan.</li>
                            <li>Jika kartu hilang atau rusak, segera hubungi staf kasir / resepsionis gym.</li>
                            <li>Wajib menjaga kebersihan fasilitas serta mengembalikan beban/alat ke tempatnya.</li>
                        </ol>
                    </div>

                    <div>
                        <div class="signature-row">
                            <div class="signature-box">
                                <!-- Area tanda tangan fisik -->
                            </div>
                            <div class="text-end" style="font-size: 1.6mm; color: #94a3b8; font-family: 'Space Mono', monospace;">
                                DOEL-{{ substr(md5($member->kode_member), 0, 8) }}
                            </div>
                        </div>

                        <div class="gym-contact-footer">
                            <div class="contact-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>DOEL GYM &bull; Pusat Kebugaran & Olahraga</span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-whatsapp"></i>
                                <span>CS / Info: 0812-3456-7890</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Printing Tips Box (Screen Only) -->
        <div class="print-tips-box no-print mt-4 card border-0 shadow-sm p-3 bg-white" style="max-width: 720px; width: 100%;">
            <div class="d-flex align-items-start gap-3">
                <div class="fs-3 text-warning">
                    <i class="bi bi-lightbulb-fill"></i>
                </div>
                <div class="small">
                    <h6 class="fw-bold mb-1 text-dark">Panduan Cetak Kartu Member Standar PVC (85.6 x 54 mm)</h6>
                    <ul class="mb-1 ps-3 text-muted">
                        <li><strong>Printer Kartu PVC:</strong> Jika menggunakan printer khusus kartu ID (misal: Evolis, Zebra, Fargo), pilih cetak <strong>Depan Saja</strong> atau <strong>Belakang Saja</strong> sesuai slot cetak.</li>
                        <li><strong>Printer Biasa (Kertas A4 / Glossy):</strong> Pilih opsi <strong>Depan & Belakang</strong>. Cetak di kertas photo tebal (230-260 gsm), lalu lipat dan laminating pouch ID card.</li>
                        <li><strong>Pengaturan Cetak Browser:</strong> Pada jendela Print, pastikan <em>Destination/Tujuan</em> dipilih, <em>Margins/Batas</em> diset ke <strong>None/Tidak ada</strong>, dan centang opsi <strong>Background graphics/Grafik latar belakang</strong>.</li>
                    </ul>
                </div>
            </div>
        </div>

    </main>

    <script>
        function setViewMode(mode) {
            const front = document.getElementById('cardFront');
            const back = document.getElementById('cardBack');
            const btnBoth = document.getElementById('btnShowBoth');
            const btnFront = document.getElementById('btnShowFront');
            const btnBack = document.getElementById('btnShowBack');

            btnBoth.classList.remove('active');
            btnFront.classList.remove('active');
            btnBack.classList.remove('active');

            if (mode === 'front') {
                front.style.display = 'flex';
                back.style.display = 'none';
                btnFront.classList.add('active');
            } else if (mode === 'back') {
                front.style.display = 'none';
                back.style.display = 'flex';
                btnBack.classList.add('active');
            } else {
                front.style.display = 'flex';
                back.style.display = 'flex';
                btnBoth.classList.add('active');
            }
        }
    </script>
</body>
</html>

