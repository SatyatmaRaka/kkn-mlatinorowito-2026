<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-sm-between gap-3">
            <h1 class="h4 mb-0 fw-bold text-dark">Logbook KKN</h1>
            <div class="d-flex gap-2">
                @if (Auth::user()->canReviewLogbook())
                    <a href="{{ route('panel.catatan-harian.export') }}" class="btn btn-success btn-sm rounded-pill px-3"><i class="bi bi-download me-1"></i>Export CSV</a>
                @endif
                @if (Auth::user()->anggota_id)
                    <a href="{{ route('panel.catatan-harian.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="bi bi-journal-plus me-1"></i> Tulis Logbook
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @if ($notifikasiReview->isNotEmpty())
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <div class="fw-semibold mb-2"><i class="bi bi-bell-fill me-1"></i> Pembaruan Catatan Harian</div>
            <ul class="mb-0 ps-3">
                @foreach ($notifikasiReview as $notifikasi)
                    <li class="mb-1">
                        {{ $notifikasi->data['pesan'] ?? 'Status catatan harian diperbarui.' }}
                        @if (! empty($notifikasi->data['logbook_id']))
                            <a href="{{ route('panel.catatan-harian.edit', $notifikasi->data['logbook_id']) }}" class="ms-1">Lihat</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        @php Auth::user()->unreadNotifications->markAsRead(); @endphp
    @endif

    <x-filter-daftar placeholder="Cari judul, deskripsi, anggota..." :reset-url="route('panel.catatan-harian.index')">
        <div class="col-md-3 col-lg-2">
            <label class="form-label small mb-1 fw-semibold">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="draft" @selected(($status ?? '') === 'draft')>Draft</option>
                <option value="submitted" @selected(($status ?? '') === 'submitted')>Menunggu Review</option>
                <option value="approved" @selected(($status ?? '') === 'approved')>Disetujui</option>
                <option value="rejected" @selected(($status ?? '') === 'rejected')>Perlu Revisi</option>
            </select>
        </div>
    </x-filter-daftar>

    <div class="premium-card border-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3">Tanggal</th>
                        @if (Auth::user()->canReviewLogbook())
                            <th class="py-3">Anggota</th>
                        @endif
                        <th class="py-3">Judul</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logbooks as $logbook)
                        <tr>
                            <td class="ps-4 py-3">{{ $logbook->tanggal->locale('id')->translatedFormat('d M Y') }}</td>
                            @if (Auth::user()->canReviewLogbook())
                                <td class="py-3" style="max-width: 160px;">
                                    <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $logbook->anggota->nama }}">{{ $logbook->anggota->nama }}</span>
                                </td>
                            @endif
                            <td class="py-3 fw-semibold" style="max-width: 220px;">
                                <span class="d-inline-block text-truncate" style="max-width: 210px;" title="{{ $logbook->judul }}">{{ $logbook->judul }}</span>
                            </td>
                            <td class="py-3">
                                @php
                                    $badges = [
                                        'draft' => ['class' => 'secondary', 'label' => 'Draft'],
                                        'submitted' => ['class' => 'warning', 'label' => 'Menunggu Review'],
                                        'approved' => ['class' => 'success', 'label' => 'Disetujui'],
                                        'rejected' => ['class' => 'danger', 'label' => 'Perlu Revisi'],
                                    ];
                                    $badge = $badges[$logbook->status] ?? ['class' => 'secondary', 'label' => ucfirst($logbook->status)];
                                @endphp
                                <span class="badge bg-{{ $badge['class'] }}">{{ $badge['label'] }}</span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                @if ($logbook->isEditableBy(Auth::user()))
                                    <a href="{{ route('panel.catatan-harian.edit', $logbook) }}" class="btn btn-sm btn-light rounded-circle" title="Edit"><i class="bi bi-pencil-square"></i></a>
                                @endif
                                @if (Auth::user()->canReviewLogbook() && $logbook->status === 'submitted')
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#review-{{ $logbook->id }}">Review</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->canReviewLogbook() ? 5 : 4 }}" class="text-center text-muted py-5">Belum ada logbook.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($logbooks->hasPages())
            <div class="p-3 border-top">{{ $logbooks->links() }}</div>
        @endif
    </div>

    @if (Auth::user()->canReviewLogbook())
        @foreach ($logbooks as $logbook)
            @if ($logbook->status === 'submitted')
                <div class="modal fade" id="review-{{ $logbook->id }}" tabindex="-1" aria-labelledby="review-label-{{ $logbook->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <form method="POST" action="{{ route('panel.catatan-harian.review', $logbook) }}">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold" id="review-label-{{ $logbook->id }}">Review Logbook</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="bg-light rounded-3 p-3 mb-3">
                                        <div class="small text-muted mb-1">{{ $logbook->anggota->nama }} · {{ $logbook->tanggal->locale('id')->translatedFormat('d F Y') }}</div>
                                        <p class="fw-semibold mb-1">{{ $logbook->judul }}</p>
                                        <p class="small text-muted mb-0">{{ $logbook->deskripsi }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keputusan</label>
                                        <select name="status" class="form-select" required>
                                            <option value="approved">Setujui</option>
                                            <option value="rejected">Tolak / Minta Revisi</option>
                                        </select>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                                        <textarea name="catatan_reviewer" class="form-control" rows="3" placeholder="Catatan untuk anggota jika perlu revisi..."></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Keputusan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</x-app-layout>
