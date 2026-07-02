    {{-- Section 5: Program Kerja --}}
    <section id="proker" class="py-5">
        <div class="container px-3 px-md-5">
            <div class="text-center mb-5">
                <h2 class="section-title h2 mb-0">Program Kerja</h2>
                <div class="section-title-accent"></div>
            </div>

            <div class="row g-4">
                @forelse ($programKerja as $item)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="premium-card h-100 position-relative">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="position-absolute top-0 end-0 m-3">
                                    @if ($item->status === 'Aktif')
                                        <span class="badge bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                                    @endif
                                </div>

                                <div class="proker-icon mb-3 text-center pt-2">
                                    {{ $item->icon ?? '­ƒôï' }}
                                </div>

                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                <p class="text-muted small flex-grow-1 mb-3">
                                    {{ Str::limit($item->deskripsi, 120) }}
                                </p>

                                <a
                                    href="{{ route('detail.proker', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary w-100 mt-auto"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted mb-0">
                            Program kerja akan segera diumumkan.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
