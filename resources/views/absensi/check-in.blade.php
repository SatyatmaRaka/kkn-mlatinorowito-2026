<x-layouts.public>
    <x-slot:title>Absensi KKN</x-slot:title>

    <section class="py-5" style="padding-top: 120px !important; min-height: 70vh;">
        <div class="container px-3" style="max-width: 520px;">
            <div class="premium-card border-0 shadow p-4 p-md-5">
                @if (session('success'))
                    <div class="alert alert-success border-0">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger border-0">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                        <i class="bi bi-qr-code-scan fs-1"></i>
                    </div>
                    <h1 class="h4 fw-bold mb-1">Absensi Posko</h1>
                    <p class="text-muted small mb-0">Halo, <strong>{{ $user->anggota?->nama ?? $user->name }}</strong></p>
                </div>

                <div class="bg-light rounded-3 p-3 mb-4 small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Jendela absensi</span>
                        <span class="fw-semibold">{{ $windowLabel }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status hari ini</span>
                        @if ($sudahAbsen)
                            <span class="badge bg-success">Sudah absen</span>
                        @elseif ($windowOpen)
                            <span class="badge bg-warning text-dark">Belum absen</span>
                        @else
                            <span class="badge bg-secondary">Di luar jam absensi</span>
                        @endif
                    </div>
                </div>

                @if ($sudahAbsen)
                    <div class="alert alert-success border-0 text-center mb-0">
                        <i class="bi bi-check-circle-fill me-1"></i> Absensi hari ini sudah tercatat. Terima kasih!
                    </div>
                @elseif ($windowOpen)
                    <form method="POST" action="{{ route('absensi.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-semibold">
                            <i class="bi bi-check2-circle me-2"></i> Konfirmasi Kehadiran
                        </button>
                    </form>
                @else
                    <p class="text-muted text-center small mb-0">Absensi hanya dapat dilakukan pada jam yang ditentukan. Hubungi koordinator jika ada kendala.</p>
                @endif

                @auth
                    <div class="text-center mt-4">
                        <a href="{{ route('dashboard') }}" class="small text-decoration-none">Kembali ke Dashboard</a>
                    </div>
                @endauth
            </div>
        </div>
    </section>
</x-layouts.public>
