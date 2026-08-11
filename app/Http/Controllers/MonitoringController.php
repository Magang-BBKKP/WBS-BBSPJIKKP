<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Investigation;
use App\Models\TindakLanjut;
use App\Exports\LaporansExport;
use Maatwebsite\Excel\Facades\Excel;
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
        $filterYear = $request->input('year');

        // Fetch distinct years from created_at
        $driver = DB::connection()->getDriverName();
        if ($driver === 'sqlite') {
            $years = Laporan::selectRaw("strftime('%Y', created_at) as year")
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->all();
        } else {
            $years = Laporan::selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->all();
        }

        // Base query for stats
        $statsQuery = Laporan::query();
        if ($filterYear) {
            $statsQuery->whereYear('created_at', $filterYear);
        }

        // Card statistics
        $stats = [
            'total'       => (clone $statsQuery)->count(),
            'menunggu'    => (clone $statsQuery)->where('status', Laporan::STATUS_MENUNGGU)->count(),
            'verifikasi'  => (clone $statsQuery)->where('status', Laporan::STATUS_VERIFIKASI)->count(),
            'valid'       => (clone $statsQuery)->where('status', Laporan::STATUS_VALID)->count(),
            'investigasi' => (clone $statsQuery)->where('status', Laporan::STATUS_INVESTIGASI)->count(),
            'ditolak'     => (clone $statsQuery)->where('status', Laporan::STATUS_DITOLAK)->count(),
            'selesai'     => (clone $statsQuery)->where('status', Laporan::STATUS_SELESAI)->count(),
        ];

        $pctSelesai = $stats['total'] > 0
            ? round(($stats['selesai'] / $stats['total']) * 100)
            : 0;

        // Per-kategori breakdown
        $kategoriQuery = Laporan::with('kategori')
            ->select('kategori_id', DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai"));
        if ($filterYear) {
            $kategoriQuery->whereYear('created_at', $filterYear);
        }
        $perKategori = $kategoriQuery->groupBy('kategori_id')->get();

        // Tabel laporan dengan filter
        $query = Laporan::with(['kategori', 'investigation.tindakLanjut'])
            ->orderBy('created_at', 'desc');

        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }
        if ($filterKategori) {
            $query->where('kategori_id', $filterKategori);
        }
        if ($filterYear) {
            $query->whereYear('created_at', $filterYear);
        }

        $laporans = $query->paginate(15)->withQueryString();

        $statusList = Laporan::statusLabel();
        $kategoris  = \App\Models\Kategori::orderBy('nama')->get();

        return view('monitoring.index', compact(
            'stats', 'pctSelesai', 'perKategori',
            'laporans', 'statusList', 'kategoris', 'years',
            'filterStatus', 'filterKategori', 'filterYear'
        ));
    }

    /**
     * Export monitoring list to Excel.
     */
    public function export(Request $request)
    {
        Gate::authorize('view-monitoring');

        $status = $request->input('status');
        $kategoriId = $request->input('kategori_id');
        $year = $request->input('year');

        return Excel::download(
            new LaporansExport($status, $kategoriId, $year),
            'monitoring_laporan_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
