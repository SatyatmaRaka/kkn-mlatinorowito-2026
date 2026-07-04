    {{-- Section 4: Anggota Tim --}}
    <section id="anggota" class="public-section public-section-alt">
        <div class="container px-3 px-md-5">
            <div class="section-header">
                <span class="section-eyebrow">Tim KKN</span>
                <h2 class="section-title h2">Anggota Tim</h2>
                <div class="section-title-accent"></div>
                <p class="section-lead">Kenali anggota kelompok KKN Mlatinorowito 2026 beserta peran dan divisi masing-masing.</p>
            </div>

            @php
                $avatarColors = ['#0f172a', '#1d4ed8', '#0369a1', '#d97706', '#059669', '#7c3aed', '#be123c', '#0d9488'];
            @endphp

            <div class="row g-4">
                @forelse ($anggota as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="premium-card member-card h-100">
                            <div class="card-body text-center p-4 d-flex flex-column">
                                @if ($item->foto)
                                    <img
                                        src="{{ asset('storage/' . $item->foto) }}"
                                        alt="{{ $item->nama }}"
                                        class="rounded-circle object-fit-cover mx-auto mb-3 avatar-circle"
                                    >
                                @else
                                    <div
                                        class="avatar-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="background: linear-gradient(135deg, {{ $avatarColors[$loop->index % count($avatarColors)] }}, rgba(15,23,42,0.75));"
                                    >
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                @endif

                                <h5 class="fw-bold mb-1">{{ $item->nama }}</h5>
                                <p class="text-muted small mb-3">{{ $item->jurusan }}</p>

                                <div class="mb-3">
                                    <x-jabatan-badge :jabatan="$item->jabatan" />
                                </div>

                                <a
                                    href="{{ route('detail.anggota', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill w-100 mt-auto"
                                >
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state-card">
                            Data anggota akan segera ditampilkan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
