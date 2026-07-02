<x-app-layout>
    <x-slot name="header">QR Code Absensi Posko</x-slot>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4 p-md-5 text-center">
                <span class="badge bg-primary mb-3">{{ $tanggalLabel }}</span>
                <h2 class="h5 fw-bold mb-2">QR Absensi Hari Ini</h2>
                <p class="text-muted small mb-4">Token berubah setiap hari. Cetak pagi hari atau tampilkan di tablet posko.</p>

                <div id="qrcode" class="d-flex justify-content-center mb-3"></div>
                <p class="small text-muted mb-1">Jam absensi: <strong>{{ $windowLabel }}</strong></p>
                <p class="small text-break text-muted mb-4">{{ $checkInUrl }}</p>

                <div class="d-flex gap-2 justify-content-center flex-wrap">
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="window.print()">
                        <i class="bi bi-printer me-1"></i> Cetak QR
                    </button>
                    <a href="{{ route('panel.absensi.display') }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-tablet me-1"></i> Mode Tablet
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="premium-card border-0 p-4">
                <h2 class="h5 fw-bold mb-3">Panduan Absensi QR</h2>
                <ol class="text-muted small mb-4 ps-3">
                    <li class="mb-2">QR <strong>berubah otomatis setiap hari</strong> — cetak ulang atau pakai mode tablet.</li>
                    <li class="mb-2">Setiap anggota scan QR dengan kamera HP.</li>
                    <li class="mb-2">Login dengan akun pribadi (bukan titip teman).</li>
                    <li class="mb-2">Tekan <strong>Konfirmasi Kehadiran</strong> dalam jam absensi.</li>
                    <li>Koordinator pantau rekap di menu <strong>Rekap Absensi</strong>.</li>
                </ol>
                <div class="alert alert-info border-0 small mb-0">
                    <strong>Rekomendasi:</strong> Pasang tablet di posko dengan halaman <em>Mode Tablet</em> agar QR selalu terbaru tanpa cetak ulang.
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                .admin-sidebar, .admin-mobile-topbar, header, .btn, .col-lg-6:last-child { display: none !important; }
                .admin-main { margin-left: 0 !important; padding: 0 !important; }
                .premium-card { box-shadow: none !important; border: 2px solid #000 !important; }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            new QRCode(document.getElementById('qrcode'), {
                text: @json($checkInUrl),
                width: 256,
                height: 256,
                correctLevel: QRCode.CorrectLevel.H
            });
        </script>
    @endpush
</x-app-layout>
