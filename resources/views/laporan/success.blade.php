@extends('layouts.landing')

@section('title', 'Laporan Berhasil Dikirim - WBS BBSPJIKKP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
@endpush

@section('content')
<div class="laporan-page py-5">
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-7 col-md-9">

    <div class="laporan-panel text-center">
        <div class="laporan-panel-body py-5">

            {{-- Success Icon --}}
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <h2 class="mb-2" style="font-size:1.6rem;">Laporan Berhasil Dikirim!</h2>
            <p class="text-muted mb-4" style="font-size:.95rem;">
                Terima kasih. Laporan Anda telah kami terima dan akan segera diverifikasi oleh Tim WBS BBSPJIKKP.
            </p>

            {{-- Nomor Registrasi --}}
            <div class="nomor-registrasi-box mb-3">
                <div class="nomor-registrasi-label">Nomor Registrasi Laporan</div>
                <div class="nomor-registrasi-value" id="nomorDisplay">{{ $nomor }}</div>
                <button class="btn btn-sm btn-outline-primary mt-2 rounded-pill" onclick="copyNomor()" id="copyBtn">
                    <i class="bi bi-clipboard me-1"></i> Salin Nomor
                </button>
            </div>

            {{-- Tracking Token --}}
            <div class="token-box mb-4">
                <div class="d-flex align-items-start gap-3">
                    <i class="bi bi-key-fill text-primary mt-1" style="font-size:1.2rem; flex-shrink:0;"></i>
                    <div class="text-start">
                        <div class="fw-semibold mb-1" style="color:#1e293b; font-size:.9rem;">
                            Token Pelacakan Rahasia
                        </div>
                        <div class="fw-bold" style="font-family:'Courier New',monospace; color:#2563eb; font-size:1rem; letter-spacing:.05em;">
                            {{ $token }}
                        </div>
                        <small class="text-muted d-block mt-1">
                            Simpan token ini dengan aman. Gunakan untuk melacak status laporan tanpa perlu login.
                        </small>
                    </div>
                </div>
            </div>

            {{-- Anonim Info --}}
            @if($isAnonim)
            <div class="d-flex align-items-center gap-2 justify-content-center mb-4">
                <i class="bi bi-shield-check text-success"></i>
                <small class="text-success fw-medium">Laporan dikirim secara anonim — identitas Anda terlindungi</small>
            </div>
            @endif

            {{-- What's Next --}}
            <div class="text-start p-3 rounded-3 mb-4" style="background:#f8fafc; border:1px solid #e2e8f0;">
                <div class="fw-semibold mb-2" style="font-size:.9rem; color:#1e293b;">
                    <i class="bi bi-arrow-right-circle me-1 text-primary"></i> Proses Selanjutnya
                </div>
                <ol class="ps-3 mb-0" style="font-size:.85rem; color:#475569; line-height:2;">
                    <li>Tim WBS akan memverifikasi laporan Anda (maks. 5 hari kerja)</li>
                    <li>Jika diperlukan klarifikasi, tim akan menghubungi Anda</li>
                    <li>Laporan valid akan diteruskan ke Kepala BBSPJIKKP</li>
                    <li>Proses investigasi akan dilakukan oleh Tim Investigasi</li>
                    <li>Tindak lanjut akan ditetapkan dan dimonitor</li>
                </ol>
            </div>

            {{-- Actions --}}
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('laporan.create') }}" class="btn-laporan-secondary">
                    <i class="bi bi-plus-lg"></i> Buat Laporan Baru
                </a>
                <a href="{{ route('tracking.index') }}" class="btn-laporan-primary">
                    <i class="bi bi-search"></i> Lacak Status Laporan
                </a>
            </div>

        </div>
    </div>

    {{-- Back to home --}}
    <div class="text-center mt-3">
        <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-house me-1"></i> Kembali ke Beranda
        </a>
    </div>

</div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
function copyNomor() {
    const nomor = '{{ $nomor }}';
    navigator.clipboard.writeText(nomor).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i class="bi bi-clipboard-check me-1"></i> Tersalin!';
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-clipboard me-1"></i> Salin Nomor';
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 2000);
    });
}
</script>
@endpush
