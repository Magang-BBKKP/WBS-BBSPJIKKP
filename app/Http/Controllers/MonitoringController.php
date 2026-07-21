<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Investigation;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-monitoring');

        $filterStatus = $request->input('status');
        $filterKategori = $request->input('kategori_id');

        // Card statistics
        $stats = [
            'total'       => Laporan::count(),
            'menunggu'    => Laporan::where('status', Laporan::STATUS_MENUNGGU)->count(),
            'verifikasi'  => Laporan::where('status', Laporan::STATUS_VERIFIKASI)->count(),
            'valid'       => Laporan::where('status', Laporan::STATUS_VALID)->count(),
            'investigasi' => Laporan::where('status', Laporan::STATUS_INVESTIGASI)->count(),
            'ditolak'     => Laporan::where('status', Laporan::STATUS_DITOLAK)->count(),
            'selesai'     => Laporan::where('status', Laporan::STATUS_SELESAI)->count(),
        ];

        $pctSelesai = $stats['total'] > 0
            ? round(($stats['selesai'] / $stats['total']) * 100)
            : 0;

        // Per-kategori breakdown
        $perKategori = Laporan::with('kategori')
            ->select('kategori_id', DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai"))
            ->groupBy('kategori_id')
            ->with('kategori')
            ->get();

        // Tabel laporan dengan filter
        $query = Laporan::with(['kategori', 'investigation.tindakLanjut'])
            ->orderBy('created_at', 'desc');

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }
        if ($filterKategori) {
            $query->where('kategori_id', $filterKategori);
        }

        $laporans = $query->paginate(15)->withQueryString();

        $statusList = Laporan::statusLabel();
        $kategoris  = \App\Models\Kategori::orderBy('nama')->get();

        return view('monitoring.index', compact(
            'stats', 'pctSelesai', 'perKategori',
            'laporans', 'statusList', 'kategoris',
            'filterStatus', 'filterKategori'
        ));
    }
}
