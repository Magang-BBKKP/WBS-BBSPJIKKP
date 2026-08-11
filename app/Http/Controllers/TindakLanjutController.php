<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Investigation;
use App\Models\TindakLanjut;
use App\Models\LaporanTimeline;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class TindakLanjutController extends Controller
{
    /**
     * Daftar investigasi yang sudah selesai dan siap ditetapkan tindak lanjut.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-tindak-lanjut');

        $search = $request->input('search');

        $query = Investigation::with(['laporan.kategori', 'investigator', 'tindakLanjut'])
            ->where('status', Investigation::STATUS_COMPLETED);

        if ($search) {
            $query->whereHas('laporan', function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%");
            });
        }

        $investigations = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('tindak-lanjut.index', compact('investigations', 'search'));
    }

    /**
     * Detail hasil investigasi + form penetapan tindak lanjut.
     */
    public function show($id)
    {
        Gate::authorize('view-tindak-lanjut');

        $investigation = Investigation::with([
            'laporan.kategori',
            'laporan.buktis',
            'investigator',
            'timelines',
            'documents',
            'tindakLanjut.ditetapkanOleh',
        ])->findOrFail($id);

        $jenisList = TindakLanjut::JENIS;

        return view('tindak-lanjut.show', compact('investigation', 'jenisList'));
    }

    /**
     * Simpan tindak lanjut dan selesaikan laporan.
     */
    public function store(Request $request, $id)
    {
        Gate::authorize('create-tindak-lanjut');

        $request->validate([
            'jenis_tindakan'        => 'required|in:' . implode(',', array_keys(TindakLanjut::JENIS)),
            'jenis_tindakan_kustom' => 'nullable|required_if:jenis_tindakan,lainnya|string|max:255',
            'keterangan'            => 'nullable|string|max:2000',
            'dokumen_tindakan'      => 'nullable|file|mimes:pdf,docx|max:5120',
        ]);

        $investigation = Investigation::findOrFail($id);

        if ($investigation->tindakLanjut) {
            return back()->with('error', 'Tindak lanjut untuk investigasi ini sudah ditetapkan.');
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen_tindakan')) {
            $file = $request->file('dokumen_tindakan');
            $fileName = 'tindak_lanjut_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dokumenPath = $file->storeAs('tindak_lanjuts', $fileName, 'local');
        }

        DB::transaction(function () use ($investigation, $request, $dokumenPath) {
            $laporan = $investigation->laporan;

            $jenis = $request->jenis_tindakan;
            if ($jenis === 'lainnya') {
                $jenis = $request->jenis_tindakan_kustom;
            }

            // Simpan tindak lanjut
            TindakLanjut::create([
                'laporan_id'      => $laporan->id,
                'investigation_id'=> $investigation->id,
                'jenis_tindakan'  => $jenis,
                'keterangan'      => $request->keterangan,
                'dokumen'         => $dokumenPath,
                'ditetapkan_oleh' => auth()->id(),
                'ditetapkan_pada' => now(),
            ]);

            // Update status laporan ke selesai
            $laporan->update(['status' => Laporan::STATUS_SELESAI]);

            // Timeline
            $jenisLabel = TindakLanjut::JENIS[$jenis] ?? $jenis;
            LaporanTimeline::create([
                'laporan_id'  => $laporan->id,
                'status'      => Laporan::STATUS_SELESAI,
                'title'       => 'Tindak Lanjut Ditetapkan',
                'description' => "Kepala BBSPJIKKP menetapkan tindak lanjut: {$jenisLabel}. Laporan dinyatakan selesai.",
            ]);

            // Audit log
            AuditLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'Tetapkan Tindak Lanjut',
                'description' => "Tindak lanjut '{$jenisLabel}' ditetapkan untuk laporan #{$laporan->nomor_registrasi}.",
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        });

        return redirect()->route('tindak-lanjut.index')
            ->with('success', 'Tindak lanjut berhasil ditetapkan. Laporan dinyatakan selesai.');
    }

    /**
     * Download the action document securely.
     */
    public function downloadDocument($id)
    {
        Gate::authorize('view-tindak-lanjut');

        $tindakLanjut = TindakLanjut::where('investigation_id', $id)->firstOrFail();

        $filePath = $tindakLanjut->dokumen;

        if (!$filePath || !\Illuminate\Support\Facades\Storage::disk('local')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        $fileName = basename($filePath);
        return \Illuminate\Support\Facades\Storage::disk('local')->download($filePath, $fileName);
    }
}
