<x-layouts.public>
    <x-slot:title>{{ $anggota->nama }} - Tim KKN Mlatinorowito 2026</x-slot:title>
    <x-slot:description>{{ $anggota->nama }} merupakan {{ $anggota->jabatan }} di program studi {{ $anggota->jurusan }} UMK, tergabung dalam tim KKN Mlatinorowito 2026.</x-slot:description>
    <section class="py-5 bg-light" style="padding-top: 100px !important;">
        <div class="container px-3 px-md-5">
            <a href="{{ url('/#anggota') }}"
               class="d-inline-flex align-items-center gap-2 text-decoration-none mb-4 px-4 py-2 rounded-pill fw-semibold"
               style="background:#1a2e4a; color:#fff; font-size:0.9rem; transition: background 0.2s, transform 0.15s;"
               onmouseover="this.style.background='#0d1f33'; this.style.transform='translateX(-3px)'"
               onmouseout="this.style.background='#1a2e4a'; this.style.transform='translateX(0)'">
                <span style="font-size:1rem;">&#8592;</span> Kembali ke Anggota Tim
            </a>

            @php
                $avatarColors = ['#003366', '#1a5c99', '#2d7ab8', '#e67e22', '#27ae60', '#8e44ad', '#c0392b', '#16a085'];
                $avatarColor = $avatarColors[($anggota->urutan - 1) % count($avatarColors)];
            @endphp

            <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px;">
                <div class="card-body p-4 p-md-5 text-center">
                    @if ($anggota->foto)
                        <img
                            src="{{ asset('storage/' . $anggota->foto) }}"
                            alt="{{ $anggota->nama }}"
                            class="rounded-circle object-fit-cover mx-auto mb-4"
                            style="width: 150px; height: 150px;"
                        >
                    @else
                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4 text-white fw-bold"
                            style="width: 150px; height: 150px; font-size: 2.5rem; background-color: {{ $avatarColor }};"
                        >
                            {{ strtoupper(substr($anggota->nama, 0, 2)) }}
                        </div>
                    @endif

                    <h1 class="h3 fw-bold mb-1">{{ $anggota->nama }}</h1>

                    @if ($anggota->jabatan === 'Koordinator Desa')
                        <span class="badge bg-warning text-dark mb-3">{{ $anggota->jabatan }}</span>
                    @else
                        <span class="badge bg-secondary mb-3">{{ $anggota->jabatan }}</span>
                    @endif

                    @if ($anggota->nim)
                        <p class="text-muted mb-2">NIM: {{ $anggota->nim }}</p>
                    @endif
                    <p class="text-muted mb-4">{{ $anggota->jurusan }} - Universitas Muria Kudus</p>

                    <div class="text-start border-top pt-4">
                        <h2 class="h6 fw-semibold text-uppercase text-muted mb-3">Deskripsi</h2>
                        <p class="mb-0">
                            {{ $anggota->deskripsi ?: 'Belum ada deskripsi.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
