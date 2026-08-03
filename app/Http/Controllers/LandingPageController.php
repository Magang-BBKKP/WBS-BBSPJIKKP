<?php

namespace App\Http\Controllers;

use App\Models\Laporan;

class LandingPageController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->can('view-dashboard')) {
            return redirect()->route('dashboard');
        }

        $totalReports = Laporan::count();
        $fallbackColors = [
            '#0a4282', '#16a34a', '#f59e0b', '#dc2626', '#00a6d6',
            '#6f42c1', '#d63384', '#64748b', '#0f766e', '#ea580c',
        ];

        $reportStats = Laporan::with('kategori')
            ->selectRaw('kategori_id, COUNT(*) as total')
            ->groupBy('kategori_id')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item, $index) use ($totalReports, $fallbackColors) {
                return [
                    'label' => $item->kategori->nama ?? 'Tidak Berkategori',
                    'value' => (int) $item->total,
                    'percent' => $totalReports > 0 ? round(($item->total / $totalReports) * 100, 1) : 0,
                    'color' => $item->kategori->warna ?? $fallbackColors[$index % count($fallbackColors)],
                ];
            })
            ->values();

        return view('landing.index', compact('reportStats', 'totalReports'));
    }
}
