    {{-- Section 5: Program Kerja --}}
    <section id="proker" class="public-section public-section-white">
        <div class="container px-3 px-md-5">
            <div class="section-header">
                <span class="section-eyebrow">Fokus Pengabdian</span>
                <h2 class="section-title h2">Program Kerja</h2>
                <div class="section-title-accent"></div>
                <p class="section-lead">Program unggulan yang dirancang untuk memberi dampak langsung kepada masyarakat Kelurahan Mlatinorowito.</p>
            </div>

            <div class="row g-4">
                @forelse ($programKerja as $item)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="premium-card h-100 position-relative">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="position-absolute top-0 end-0 m-3">
                                    @if ($item->status === 'Aktif')
                                        <span class="badge rounded-pill bg-success">{{ $item->status }}</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning text-dark">{{ $item->status }}</span>
                                    @endif
                                </div>

                                @if ($item->icon)
                                    <div class="proker-icon mb-3 text-center pt-2">
                                        {{ $item->icon }}
                                    </div>
                                @endif

                                <h5 class="fw-bold mb-2">{{ $item->judul }}</h5>
                                @if ($item->tema)
                                    <p class="text-primary small fw-medium mb-2">{{ $item->tema }}</p>
                                @endif
                                <p class="text-muted small flex-grow-1 mb-3">
                                    {{ Str::limit($item->deskripsi, 120) }}
                                </p>

                                <a
                                    href="{{ route('detail.proker', $item->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill w-100 mt-auto"
                                >
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state-card">
                            Program kerja akan segera diumumkan.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
