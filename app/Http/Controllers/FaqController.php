<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-master-data');

        $search = $request->input('search');
        $query  = Faq::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('order')->orderBy('created_at', 'desc')->paginate(15);

        return view('master-data.faq.index', [
            'faqs' => $faqs,
            'search' => $search,
            'tabs' => $this->tabs('faq'),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create-master-data');

        $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string|max:2000',
            'order'     => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        Faq::create([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Tambah FAQ',
            'description' => "FAQ '{$request->question}' ditambahkan.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('master-data.faq.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function update(Request $request, Faq $faq)
    {
        Gate::authorize('update-master-data');

        $request->validate([
            'question'  => 'required|string|max:255',
            'answer'    => 'required|string|max:2000',
            'order'     => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $faq->update([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'order'     => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Edit FAQ',
            'description' => "FAQ '{$request->question}' diperbarui.",
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return redirect()->route('master-data.faq.index')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        Gate::authorize('delete-master-data');

        $question = $faq->question;
        $faq->delete();

        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'Hapus FAQ',
            'description' => "FAQ '{$question}' dihapus.",
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()->route('master-data.faq.index')->with('success', 'FAQ berhasil dihapus.');
    }

    private function tabs(string $active): array
    {
        return array_merge([
            'kategori' => [
                'title' => 'Kategori',
                'url' => route('master-data.index'),
                'active' => $active === 'kategori',
            ],
        ], collect([
            'unit' => ['title' => 'Unit', 'route' => 'master-data.items.index'],
            'status' => ['title' => 'Status', 'route' => 'master-data.items.index'],
            'prioritas' => ['title' => 'Prioritas', 'route' => 'master-data.items.index'],
        ])->mapWithKeys(function ($meta, $type) use ($active) {
            return [$type => [
                'title' => $meta['title'],
                'url' => route($meta['route'], $type),
                'active' => $active === $type,
            ]];
        })->all(), [
            'faq' => [
                'title' => 'FAQ',
                'url' => route('master-data.faq.index'),
                'active' => $active === 'faq',
            ],
        ]);
    }
}
