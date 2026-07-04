@props([
    'action' => null,
    'resetUrl' => null,
    'placeholder' => 'Ketik kata kunci...',
    'showSearch' => true,
])

@php
    $hasFilter = collect(request()->query())->except('page')->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
@endphp

<form method="GET" action="{{ $action ?? url()->current() }}" class="premium-card border-0 p-3 mb-4">
    <div class="row g-2 align-items-end">
        @if ($showSearch)
            <div class="col-md-4 col-lg-3">
                <label class="form-label small mb-1 fw-semibold">Pencarian</label>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="{{ $placeholder }}">
            </div>
        @endif

        {{ $slot }}

        <div class="col-auto ms-md-auto d-flex gap-2 flex-wrap pb-1">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                <i class="bi bi-funnel me-1"></i> Terapkan
            </button>
            @if ($hasFilter && $resetUrl)
                <a href="{{ $resetUrl }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Reset</a>
            @endif
        </div>
    </div>
</form>
