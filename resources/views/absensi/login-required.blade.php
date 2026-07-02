<x-layouts.public>
    <x-slot:title>Absensi KKN - Login Diperlukan</x-slot:title>

    <section class="py-5" style="padding-top: 120px !important; min-height: 70vh;">
        <div class="container px-3" style="max-width: 480px;">
            <div class="premium-card border-0 shadow p-4 p-md-5 text-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 72px; height: 72px;">
                    <i class="bi bi-qr-code-scan fs-1"></i>
                </div>
                <h1 class="h4 fw-bold mb-2">Absensi Posko KKN</h1>
                <p class="text-muted mb-4">Silakan login dengan akun anggota Anda untuk melakukan absensi.</p>
                <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">Login Sekarang</a>
            </div>
        </div>
    </section>
</x-layouts.public>
