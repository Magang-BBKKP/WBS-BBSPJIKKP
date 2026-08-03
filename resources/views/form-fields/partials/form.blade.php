<div class="field-form">
    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label fw-semibold small">Pertanyaan / Label <span class="text-danger">*</span></label>
            <input type="text" name="label" class="form-control rounded-3" value="{{ old('label', $field?->label) }}" placeholder="Contoh: Nama saksi" required>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Tipe Jawaban <span class="text-danger">*</span></label>
            @if($field?->isBaseField())
            <input type="hidden" name="type" value="{{ $field->type }}">
            <select class="form-select rounded-3" disabled>
                <option>{{ $types[$field->type] ?? $field->type }}</option>
            </select>
            <div class="form-text">Tipe field bawaan dikunci agar penyimpanan laporan tetap sesuai.</div>
            @else
            <select name="type" class="form-select rounded-3" data-field-type required>
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" {{ old('type', $field?->type ?? 'text') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @endif
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold small">Placeholder</label>
            <input type="text" name="placeholder" class="form-control rounded-3" value="{{ old('placeholder', $field?->placeholder) }}" placeholder="Teks bantuan di dalam input">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Urutan</label>
            <input type="number" name="sort_order" class="form-control rounded-3" value="{{ old('sort_order', $field?->sort_order ?? 0) }}" min="0" max="9999">
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold small">Keterangan Bantuan</label>
            <textarea name="help_text" class="form-control rounded-3" rows="2" placeholder="Penjelasan singkat untuk pelapor">{{ old('help_text', $field?->help_text) }}</textarea>
        </div>
        <div class="col-12" data-options-wrapper style="{{ $field?->isBaseField() ? 'display:none' : '' }}">
            <label class="form-label fw-semibold small">Pilihan Jawaban</label>
            <textarea name="options_text" class="form-control rounded-3" rows="4" placeholder="Satu pilihan per baris">{{ old('options_text', $field?->options ? implode("\n", $field->options) : '') }}</textarea>
            <div class="form-text">Dipakai untuk dropdown, pilihan ganda, dan kotak centang.</div>
        </div>
        <div class="col-12 d-flex flex-wrap gap-4">
            <div class="form-check form-switch">
                <input type="checkbox" name="is_required" value="1" class="form-check-input" id="required{{ $field?->id ?? 'New' }}" {{ old('is_required', $field?->is_required ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="required{{ $field?->id ?? 'New' }}">Wajib diisi</label>
            </div>
            <div class="form-check form-switch">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active{{ $field?->id ?? 'New' }}" {{ old('is_active', $field?->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="active{{ $field?->id ?? 'New' }}">Aktif di form pelaporan</label>
            </div>
        </div>
    </div>
</div>
