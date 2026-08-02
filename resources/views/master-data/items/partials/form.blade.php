<div class="mb-3">
    <label class="form-label fw-semibold small">Nama <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $item?->name) }}" placeholder="Nama data..." required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold small">Deskripsi</label>
    <textarea name="description" class="form-control rounded-3" rows="2" placeholder="Keterangan singkat...">{{ old('description', $item?->description) }}</textarea>
</div>

@if($meta['uses_color'])
<div class="mb-3">
    <label class="form-label fw-semibold small">Warna</label>
    <input type="color" name="color" class="form-control form-control-color rounded-3" value="{{ old('color', $item?->color ?? '#0a4282') }}">
</div>
@endif

<div class="mb-3">
    <label class="form-label fw-semibold small">Urutan</label>
    <input type="number" name="sort_order" class="form-control rounded-3" min="0" max="9999" value="{{ old('sort_order', $item?->sort_order ?? 0) }}">
</div>

<div class="form-check form-switch">
    <input type="hidden" name="is_active" value="0">
    <input class="form-check-input" type="checkbox" role="switch" id="isActive{{ $item?->id ?? 'New' }}" name="is_active" value="1" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label small fw-semibold" for="isActive{{ $item?->id ?? 'New' }}">Aktif</label>
</div>
