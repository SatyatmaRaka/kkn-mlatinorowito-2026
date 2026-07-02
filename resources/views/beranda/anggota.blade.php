    {{-- Section 4: Anggota Tim --}}
    <section id="anggota" class="py-5 bg-light">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Anggota Tim</h2>
                <div class="section-title-accent"></div>
            </div>

            @php
                $avatarColors = ['#003366', '#1a5c99', '#2d7ab8', '#e67e22', '#27ae60', '#8e44ad', '#c0392b', '#16a085'];
            @endphp

            <div class="row g-4">
                @forelse ($anggota as $item)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="premium-card h-100">
                            <div class="card-body text-center p-4">
                                @if ($item->foto)
                                    <img
                                        src="{{ asset('storage/' . $item->foto) }}"
                                        alt="{{ $item->nama }}"
                                        class="rounded-circle object-fit-cover mx-auto mb-3 avatar-circle"
                                    >
                                @else
                                    <div
                                        class="avatar-circle rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="background-color: {{ $avatarColors[$loop->index % count($avatarColors)] }};"
                                    >
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                @endif

                                <h5 class="fw-bold mb-1">{{ $item->nama }}</h5>
                                <p class="text-muted small mb-2">{{ $item->jurusan }}</p>

                                <x-jabatan-badge :jabatan="$item->jabatan" />

                                <a
                                    href="{{ route('detail.anggota', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary w-100"
                                >
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted mb-0">
                            Data anggota akan segera ditampilkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
