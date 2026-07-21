<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Investigation;
use App\Models\User;
use App\Models\LaporanTimeline;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;

class KepalaController extends Controller
{
    /**
     * Daftar laporan valid yang belum memiliki investigasi — siap ditugaskan.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-laporan');

        $search = $request->input('search');

        $query = Laporan::with('kategori')
            ->where('status', Laporan::STATUS_VALID)
            ->whereDoesntHave('investigation');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_registrasi', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhereHas('kategori', fn($k) => $k->where('nama', 'like', "%{$search}%"));
            });
        }

        $laporans = $query->orderBy('created_at', 'desc')->paginate(10);
        $investigators = User::role('investigator')->where('is_active', true)->get();

        return view('kepala.index', compact('laporans', 'investigators', 'search'));
    }

    /**
     * Assign investigator ke laporan valid → buat record Investigation.
     */
    public function assign(Request $request, $laporan_id)
    {
        Gate::authorize('approve-investigasi');

        $request->validate([
            'investigator_id' => 'required|exists:users,id',
        ]);

        $laporan = Laporan::findOrFail($laporan_id);

        if ($laporan->status !== Laporan::STATUS_VALID) {
            return back()->with('error', 'Laporan tidak dalam status valid untuk ditugaskan.');
        }

        if ($laporan->investigation) {
            return back()->with('error', 'Laporan ini sudah memiliki investigasi.');
        }

        DB::transaction(function () use ($laporan, $request) {
            // Buat record investigasi
            Investigation::create([
                'laporan_id'     => $laporan->id,
                'investigator_id'=> $request->investigator_id,
                'assigned_by'    => auth()->id(),
                'assigned_at'    => now(),
                'status'         => Investigation::STATUS_ACTIVE,
            ]);

            // Update status laporan ke investigasi
            $laporan->update(['status' => Laporan::STATUS_INVESTIGASI]);

            // Tambah timeline
            $investigator = User::find($request->investigator_id);
            LaporanTimeline::create([
                'laporan_id'  => $laporan->id,
                'status'      => Laporan::STATUS_INVESTIGASI,
                'title'       => 'Tim Investigasi Dibentuk',
                'description' => "Kepala BBSPJIKKP menugaskan {$investigator->name} sebagai investigator untuk menangani laporan ini.",
            ]);

            // Audit log
            AuditLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'Tugaskan Investigator',
                'description' => "Kepala menugaskan investigator untuk laporan #{$laporan->nomor_registrasi}.",
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        });

        return redirect()->route('kepala.index')
            ->with('success', 'Investigator berhasil ditugaskan. Laporan masuk tahap investigasi.');
    }
}
