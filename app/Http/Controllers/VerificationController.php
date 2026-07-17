<?php

namespace App\Http\Controllers;

use App\Http\Requests\VerifyLaporanRequest;
use App\Http\Requests\ClarifyLaporanRequest;
use App\Http\Requests\RejectLaporanRequest;
use App\Services\VerificationService;
use App\Models\Laporan;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct(
        protected VerificationService $verificationService
    ) {}

    /**
     * Tampilkan daftar laporan menunggu verifikasi
     * Route: GET /verifikasi
     */
    public function index(Request $request)
    {
        // Tampilkan laporan dengan status menunggu / verifikasi,
        // mengecualikan laporan yang sedang menunggu klarifikasi dari pelapor
        $query = Laporan::with('kategori')
            ->whereIn('status', [Laporan::STATUS_MENUNGGU, Laporan::STATUS_VERIFIKASI])
            ->where(function ($q) {
                $q->whereNull('verification_status')
                  ->orWhere('verification_status', '!=', 'waiting_clarification');
            });

        // Search functionality
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // 1. Nomor registrasi
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  // 2. Judul laporan
                  ->orWhere('judul', 'like', "%{$search}%")
                  // 3. Nama pelapor jika bukan anonim
                  ->orWhere(function ($qp) use ($search) {
                      $qp->where('is_anonim', false)
                         ->where('nama_pelapor', 'like', "%{$search}%");
                  })
                  // 4. Jenis pelanggaran (kategori)
                  ->orWhereHas('kategori', function ($qk) use ($search) {
                      $qk->where('nama', 'like', "%{$search}%");
                  })
                  // 5. Terlapor (nama, jabatan, atau unit)
                  ->orWhere('nama_terlapor', 'like', "%{$search}%")
                  ->orWhere('jabatan_terlapor', 'like', "%{$search}%")
                  ->orWhere('unit_terlapor', 'like', "%{$search}%")
                  // 6. Status laporan (mapping teks bahasa Indonesia ke database value)
                  ->orWhere(function ($qs) use ($search) {
                      if (stripos('menunggu verifikasi', $search) !== false || stripos('menunggu', $search) !== false) {
                          $qs->orWhere('status', Laporan::STATUS_MENUNGGU);
                      }
                      if (stripos('sedang diverifikasi', $search) !== false || stripos('verifikasi', $search) !== false || stripos('sedang', $search) !== false) {
                          $qs->orWhere('status', Laporan::STATUS_VERIFIKASI);
                      }
                      if (stripos('terverifikasi', $search) !== false || stripos('valid', $search) !== false) {
                          $qs->orWhere('status', Laporan::STATUS_VALID);
                      }
                      if (stripos('ditolak', $search) !== false) {
                          $qs->orWhere('status', Laporan::STATUS_DITOLAK);
                      }
                  });
            });
        }

        $laporans = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('verification.index', compact('laporans', 'search'));
    }

    /**
     * Tampilkan detail laporan untuk proses verifikasi
     * Route: GET /verifikasi/{id}
     */
    public function show($id)
    {
        $laporan = $this->verificationService->getLaporanForVerification($id);

        return view('verification.show', compact('laporan'));
    }

    /**
     * Aksi A: Validasi Laporan
     * Route: POST /verifikasi/{id}/validate
     */
    public function validateReport(VerifyLaporanRequest $request, $id)
    {
        $this->verificationService->validateReport($id, $request->validated());

        return redirect()->route('verifikasi.index')
            ->with('success', 'Laporan berhasil divalidasi dan ditandai sebagai Terverifikasi.');
    }

    /**
     * Aksi B: Meminta Klarifikasi
     * Route: POST /verifikasi/{id}/clarify
     */
    public function clarifyReport(ClarifyLaporanRequest $request, $id)
    {
        $this->verificationService->clarifyReport($id, $request->validated());

        return redirect()->route('verifikasi.index')
            ->with('success', 'Permintaan klarifikasi telah dikirim ke pelapor.');
    }

    /**
     * Aksi C: Menolak Laporan
     * Route: POST /verifikasi/{id}/reject
     */
    public function rejectReport(RejectLaporanRequest $request, $id)
    {
        $this->verificationService->rejectReport($id, $request->validated());

        return redirect()->route('verifikasi.index')
            ->with('success', 'Laporan telah ditolak.');
    }
}
