<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\ReportFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ReportFormFieldController extends Controller
{
    public function index()
    {
        Gate::authorize('view-master-data');

        $fields = ReportFormField::orderBy('sort_order')->orderBy('label')->get();

        return view('form-fields.index', [
            'fields' => $fields,
            'types' => ReportFormField::TYPES,
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create-master-data');

        $validated = $this->validateField($request);
        $validated['name'] = ReportFormField::makeName($validated['label']);
        $validated['source'] = ReportFormField::SOURCE_CUSTOM;
        $validated['options'] = $this->normalizeOptions($request);
        unset($validated['options_text']);
        if ($this->requiresOptions($validated['type']) && empty($validated['options'])) {
            return back()->withErrors(['options_text' => 'Pilihan jawaban wajib diisi untuk tipe ini.'])->withInput();
        }
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $field = ReportFormField::create($validated);

        $this->log($request, 'Tambah Field Form', "Field '{$field->label}' ditambahkan ke form pelaporan.");

        return redirect()->route('form-fields.index')->with('success', 'Field form berhasil ditambahkan.');
    }

    public function update(Request $request, ReportFormField $formField)
    {
        Gate::authorize('update-master-data');

        $validated = $this->validateField($request);
        $validated['options'] = $this->normalizeOptions($request);
        unset($validated['options_text']);
        if (!$formField->isBaseField() && $this->requiresOptions($validated['type']) && empty($validated['options'])) {
            return back()->withErrors(['options_text' => 'Pilihan jawaban wajib diisi untuk tipe ini.'])->withInput();
        }
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $formField->update($validated);

        $this->log($request, 'Update Field Form', "Field '{$formField->label}' diperbarui.");

        return redirect()->route('form-fields.index')->with('success', 'Field form berhasil diperbarui.');
    }

    public function destroy(Request $request, ReportFormField $formField)
    {
        Gate::authorize('delete-master-data');

        $label = $formField->label;
        $formField->delete();

        $this->log($request, 'Hapus Field Form', "Field '{$label}' dihapus dari form pelaporan.");

        return redirect()->route('form-fields.index')->with('success', 'Field form berhasil dihapus.');
    }

    private function validateField(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'type' => ['required', Rule::in(array_keys(ReportFormField::TYPES))],
            'placeholder' => ['nullable', 'string', 'max:160'],
            'help_text' => ['nullable', 'string', 'max:500'],
            'options_text' => ['nullable', 'string', 'max:2000'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);
    }

    private function normalizeOptions(Request $request): ?array
    {
        if (!in_array($request->input('type'), ['select', 'radio', 'checkbox'], true)) {
            return null;
        }

        $options = collect(preg_split('/\r\n|\r|\n/', (string) $request->input('options_text')))
            ->map(fn ($option) => trim($option))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $options ?: null;
    }

    private function requiresOptions(string $type): bool
    {
        return in_array($type, ['select', 'radio', 'checkbox'], true);
    }

    private function log(Request $request, string $action, string $description): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}
