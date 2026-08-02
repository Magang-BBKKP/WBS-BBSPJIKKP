<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\AuditLog;
use App\Models\MasterDataItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    private array $itemTypes = [
        'unit' => [
            'title' => 'Unit',
            'description' => 'Kelola unit kerja yang dapat digunakan sebagai referensi laporan dan akun pengguna.',
            'route' => 'master-data.items.index',
            'uses_color' => false,
        ],
        'status' => [
            'title' => 'Status',
            'description' => 'Kelola referensi status proses WBS untuk kebutuhan monitoring dan pelaporan.',
            'route' => 'master-data.items.index',
            'uses_color' => true,
        ],
        'prioritas' => [
            'title' => 'Prioritas',
            'description' => 'Kelola tingkat prioritas laporan untuk klasifikasi risiko dan urgensi.',
            'route' => 'master-data.items.index',
            'uses_color' => true,
        ],
    ];

    public function index(Request $request)
    {
        Gate::authorize('view-master-data');

        $search = $request->input('search');
        $query  = Kategori::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $kategoris = $query->orderBy('nama')->paginate(15);

        return view('master-data.kategori.index', [
            'kategoris' => $kategoris,
            'search' => $search,
            'tabs' => $this->tabs('kategori'),
        ]);
    }

    public function items(Request $request, string $type)
    {
        Gate::authorize('view-master-data');

        abort_unless(isset($this->itemTypes[$type]), 404);

        $search = $request->input('search');
        $query = MasterDataItem::type($type);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('master-data.items.index', [
            'type' => $type,
            'meta' => $this->itemTypes[$type],
            'items' => $items,
            'search' => $search,
            'tabs' => $this->tabs($type),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create-master-data');

        $request->validate([
            'nama'      => 'required|string|max:100|unique:kategoris,nama',
            'deskripsi' => 'nullable|string|max:500',
            'warna'     => 'nullable|string|max:20',
        ]);

        Kategori::create($request->only('nama', 'deskripsi', 'warna'));

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Tambah Kategori',
            'description' => "Kategori '{$request->nama}' ditambahkan.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('master-data.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function storeItem(Request $request, string $type)
    {
        Gate::authorize('create-master-data');

        abort_unless(isset($this->itemTypes[$type]), 404);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('master_data_items', 'name')->where('type', $type),
            ],
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $validated['type'] = $type;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        MasterDataItem::create($validated);

        $this->log($request, 'Tambah ' . $this->itemTypes[$type]['title'], "{$this->itemTypes[$type]['title']} '{$validated['name']}' ditambahkan.");

        return redirect()->route('master-data.items.index', $type)->with('success', $this->itemTypes[$type]['title'] . ' berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        Gate::authorize('update-master-data');

        $request->validate([
            'nama'      => 'required|string|max:100|unique:kategoris,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:500',
            'warna'     => 'nullable|string|max:20',
        ]);

        $kategori->update($request->only('nama', 'deskripsi', 'warna'));

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Update Kategori',
            'description' => "Kategori '{$kategori->nama}' diperbarui.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('master-data.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function updateItem(Request $request, string $type, MasterDataItem $item)
    {
        Gate::authorize('update-master-data');

        abort_unless(isset($this->itemTypes[$type]) && $item->type === $type, 404);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:120',
                Rule::unique('master_data_items', 'name')->where('type', $type)->ignore($item->id),
            ],
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $item->update($validated);

        $this->log($request, 'Update ' . $this->itemTypes[$type]['title'], "{$this->itemTypes[$type]['title']} '{$item->name}' diperbarui.");

        return redirect()->route('master-data.items.index', $type)->with('success', $this->itemTypes[$type]['title'] . ' berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        Gate::authorize('delete-master-data');

        $nama = $kategori->nama;
        $kategori->delete();

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Hapus Kategori',
            'description' => "Kategori '{$nama}' dihapus.",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('master-data.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function destroyItem(string $type, MasterDataItem $item)
    {
        Gate::authorize('delete-master-data');

        abort_unless(isset($this->itemTypes[$type]) && $item->type === $type, 404);

        $name = $item->name;
        $item->delete();

        $this->log(request(), 'Hapus ' . $this->itemTypes[$type]['title'], "{$this->itemTypes[$type]['title']} '{$name}' dihapus.");

        return redirect()->route('master-data.items.index', $type)->with('success', $this->itemTypes[$type]['title'] . ' berhasil dihapus.');
    }

    private function tabs(string $active): array
    {
        return array_merge([
            'kategori' => [
                'title' => 'Kategori',
                'url' => route('master-data.index'),
                'active' => $active === 'kategori',
            ],
        ], collect($this->itemTypes)->mapWithKeys(function ($meta, $type) use ($active) {
            return [$type => [
                'title' => $meta['title'],
                'url' => route('master-data.items.index', $type),
                'active' => $active === $type,
            ]];
        })->all());
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
