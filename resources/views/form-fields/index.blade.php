@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 gap-3">
    <div>
        <h1 class="h4 fw-bold text-dark mb-1">Form Pelaporan</h1>
        <p class="text-muted small mb-0">Kelola field yang tampil di formulir laporan.</p>
    </div>
    <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addFieldModal">
        <i class="bi bi-plus-circle me-1"></i> Tambah Field
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger rounded-3 border-0 shadow-sm">
        <div class="fw-semibold mb-1">Field belum bisa disimpan.</div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 text-muted small fw-semibold">Urutan</th>
                        <th class="py-3 text-muted small fw-semibold">Label</th>
                        <th class="py-3 text-muted small fw-semibold">Tipe</th>
                        <th class="py-3 text-muted small fw-semibold">Wajib</th>
                        <th class="py-3 text-muted small fw-semibold">Status</th>
                        <th class="py-3 text-muted small fw-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fields as $field)
                        @include('form-fields.partials.row', ['field' => $field, 'types' => $types])
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            Belum ada field yang dikonfigurasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addFieldModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Tambah Field</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('form-fields.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('form-fields.partials.form', ['field' => null, 'types' => $types])
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-field-type]').forEach(function(select) {
    const wrapper = select.closest('.field-form');
    const options = wrapper.querySelector('[data-options-wrapper]');
    const toggle = function() {
        options.style.display = ['select', 'radio', 'checkbox'].includes(select.value) ? 'block' : 'none';
    };
    select.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush
