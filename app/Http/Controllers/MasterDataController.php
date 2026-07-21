<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MasterDataController extends Controller
{
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

        return view('master-data.kategori.index', compact('kategoris', 'search'));
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
}
