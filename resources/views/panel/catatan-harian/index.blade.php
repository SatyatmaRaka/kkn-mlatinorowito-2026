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
                                <td class="py-3">{{ $logbook->anggota->nama }}</td>
                            @endif
                            <td class="py-3 fw-semibold">{{ $logbook->judul }}</td>
                            <td class="py-3">
                                @php
                                    $badges = [
                                        'draft' => 'secondary',
                                        'submitted' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $badges[$logbook->status] ?? 'secondary' }}">{{ ucfirst($logbook->status) }}</span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                @if ($logbook->isEditableBy(Auth::user()))
                                    <a href="{{ route('panel.catatan-harian.edit', $logbook) }}" class="btn btn-sm btn-light rounded-circle"><i class="bi bi-pencil-square"></i></a>
                                @endif
                                @if (Auth::user()->canReviewLogbook() && $logbook->status === 'submitted')
                                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#review-{{ $logbook->id }}">Review</button>
                                @endif
                            </td>
                        </tr>

                        @if (Auth::user()->canReviewLogbook() && $logbook->status === 'submitted')
                            <div class="modal fade" id="review-{{ $logbook->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('panel.catatan-harian.review', $logbook) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Review Logbook</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="fw-semibold">{{ $logbook->judul }}</p>
                                                <p class="small text-muted">{{ $logbook->deskripsi }}</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Keputusan</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="approved">Setujui</option>
                                                        <option value="rejected">Tolak / Revisi</option>
                                                    </select>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label">Catatan</label>
                                                    <textarea name="catatan_reviewer" class="form-control" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
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
</x-app-layout>
