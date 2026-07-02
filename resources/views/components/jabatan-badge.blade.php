@php
    use App\Penunjang\TautanSosial;
@endphp

@if (TautanSosial::isJabatanPimpinan($jabatan))
    <span class="badge bg-warning text-dark {{ $class ?? 'mb-3' }}">{{ $jabatan }}</span>
@else
    <span class="badge bg-secondary {{ $class ?? 'mb-3' }}">{{ $jabatan }}</span>
@endif
