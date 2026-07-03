{{-- Notifikasi live + polling --}}
<div
    class="position-fixed top-0 end-0 p-3 d-none d-lg-block"
    style="z-index: 1050; margin-left: 250px;"
    x-data="notifikasiLive()"
    x-init="mulai()"
>
    <div class="dropdown">
        <button
            type="button"
            class="btn btn-light shadow-sm position-relative rounded-circle"
            style="width: 42px; height: 42px;"
            data-bs-toggle="dropdown"
            aria-label="Notifikasi"
        >
            <i class="bi bi-bell"></i>
            <span
                x-show="jumlah > 0"
                x-text="jumlah > 9 ? '9+' : jumlah"
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            ></span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 320px; max-height: 360px; overflow-y: auto;">
            <li class="dropdown-header d-flex justify-content-between align-items-center">
                <span>Notifikasi</span>
                <button type="button" class="btn btn-link btn-sm p-0" @click="tandaiSemua()" x-show="jumlah > 0">Tandai dibaca</button>
            </li>
            <template x-if="items.length === 0">
                <li><span class="dropdown-item-text text-muted small">Tidak ada notifikasi baru.</span></li>
            </template>
            <template x-for="item in items" :key="item.id">
                <li>
                    <a :href="item.url" class="dropdown-item small py-2" @click="tandai(item.id)">
                        <div x-text="item.pesan"></div>
                        <div class="text-muted" style="font-size: 0.75rem;" x-text="item.waktu"></div>
                    </a>
                </li>
            </template>
        </ul>
    </div>
</div>

@push('scripts')
<script>
    function notifikasiLive() {
        return {
            jumlah: 0,
            items: [],
            interval: null,
            async muat() {
                try {
                    const res = await fetch('{{ route('panel.api.live.notifikasi') }}', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    this.jumlah = data.jumlah;
                    this.items = data.items;
                } catch (e) {}
            },
            mulai() {
                this.muat();
                this.interval = setInterval(() => this.muat(), 30000);
            },
            async tandai(id) {
                await fetch('{{ route('panel.api.notifikasi.baca') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ id }),
                });
                this.muat();
            },
            async tandaiSemua() {
                await fetch('{{ route('panel.api.notifikasi.baca') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({}),
                });
                this.muat();
            },
        };
    }
</script>
@endpush
