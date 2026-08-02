<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->can('view-dashboard')) {
            return redirect()->route('dashboard');
        }

        $stats = [
            'total' => Laporan::count(),
            'disetujui' => Laporan::whereIn('status', [
                Laporan::STATUS_VALID,
                Laporan::STATUS_SELESAI,
            ])->count(),
            'proses' => Laporan::whereIn('status', [
                Laporan::STATUS_MENUNGGU,
                Laporan::STATUS_VERIFIKASI,
                Laporan::STATUS_INVESTIGASI,
            ])->count(),
            'ditolak' => Laporan::where('status', Laporan::STATUS_DITOLAK)->count(),
        ];

        $maxValue = max($stats['total'], $stats['disetujui'], $stats['proses'], $stats['ditolak'], 1);

        $reportStats = [
            [
                'label' => 'Total Pelapor',
                'value' => $stats['total'],
                'percent' => round(($stats['total'] / $maxValue) * 100),
                'icon' => 'bi-people-fill',
                'tone' => 'primary',
            ],
            [
                'label' => 'Disetujui',
                'value' => $stats['disetujui'],
                'percent' => round(($stats['disetujui'] / $maxValue) * 100),
                'icon' => 'bi-check2-circle',
                'tone' => 'success',
            ],
            [
                'label' => 'Sedang Proses',
                'value' => $stats['proses'],
                'percent' => round(($stats['proses'] / $maxValue) * 100),
                'icon' => 'bi-hourglass-split',
                'tone' => 'warning',
            ],
            [
                'label' => 'Ditolak',
                'value' => $stats['ditolak'],
                'percent' => round(($stats['ditolak'] / $maxValue) * 100),
                'icon' => 'bi-x-circle',
                'tone' => 'danger',
            ],
        ];

        return view('landing.index', compact('reportStats'));
    }
}
