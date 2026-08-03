<tr>
    <td class="px-4 py-3 text-muted small">{{ $field->sort_order }}</td>
    <td class="py-3">
        <div class="fw-semibold small">{{ $field->label }}</div>
        <div class="text-muted" style="font-size:.75rem;">{{ $field->name }}</div>
    </td>
    <td class="py-3 small">{{ $types[$field->type] ?? $field->type }}</td>
    <td class="py-3">
        <span class="badge rounded-pill text-bg-{{ $field->is_required ? 'danger' : 'secondary' }}">
            {{ $field->is_required ? 'Wajib' : 'Opsional' }}
        </span>
    </td>
    <td class="py-3">
        <span class="badge rounded-pill text-bg-{{ $field->is_active ? 'success' : 'secondary' }}">
            {{ $field->is_active ? 'Aktif' : 'Nonaktif' }}
        </span>
    </td>
    <td class="py-3 d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary rounded-3" data-bs-toggle="modal" data-bs-target="#editField{{ $field->id }}">
            <i class="bi bi-pencil"></i>
        </button>
        <form action="{{ route('form-fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Hapus field ini dari form pelaporan? Data laporan lama tetap tersimpan.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"><i class="bi bi-trash"></i></button>
        </form>
    </td>
</tr>

<div class="modal fade" id="editField{{ $field->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Field</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('form-fields.update', $field->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    @include('form-fields.partials.form', ['field' => $field, 'types' => $types])
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
