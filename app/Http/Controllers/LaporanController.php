<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\Kategori;
use App\Models\Laporan;
use App\Services\LaporanService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        protected LaporanService $laporanService
    ) {}

    /**
     * Tampilkan wizard form pelaporan (Step 1)
     * Route: GET /laporan/buat
     */
    public function create()
    {
        $kategoris = Kategori::aktif()->get();

        return view('laporan.create', compact('kategoris'));
    }

    /**
     * Proses submit laporan
     * Route: POST /laporan/buat
     */
    public function store(StoreLaporanRequest $request)
    {
        $data  = $request->validated();
        $files = $request->file('bukti', []);

        $laporan = $this->laporanService->submitLaporan($data, $files);

        // Simpan sementara ke session untuk halaman sukses
        session([
            'laporan_nomor'   => $laporan->nomor_registrasi,
            'laporan_token'   => $laporan->tracking_token,
            'laporan_is_anonim' => $laporan->is_anonim,
        ]);

        return redirect()->route('laporan.sukses');
    }

    /**
     * Halaman konfirmasi sukses setelah submit
     * Route: GET /laporan/sukses
     */
    public function success()
    {
        // Pastikan ada data session, hindari akses langsung ke halaman ini
        if (!session('laporan_nomor')) {
            return redirect()->route('laporan.create')
                ->with('info', 'Silakan isi formulir laporan terlebih dahulu.');
        }

        $nomor    = session()->pull('laporan_nomor');
        $token    = session()->pull('laporan_token');
        $isAnonim = session()->pull('laporan_is_anonim');

        return view('laporan.success', compact('nomor', 'token', 'isAnonim'));
    }

    /**
     * Laporan Saya — daftar laporan milik pelapor yang sedang login.
     * Route: GET /laporan/saya
     */
    public function myReports(Request $request)
    {
        $user = auth()->user();

        $query = Laporan::with('kategori')
            ->where('email_pelapor', $user->email)
            ->orderBy('created_at', 'desc');

        $laporans = $query->paginate(10);

        return view('laporan.saya', compact('laporans'));
    }
}
