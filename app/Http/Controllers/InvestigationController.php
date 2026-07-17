<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimelineRequest;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateInvestigationRequest;
use App\Services\InvestigationService;
use App\Models\Investigation;
use App\Models\InvestigationDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class InvestigationController extends Controller
{
    /**
     * InvestigationController constructor.
     *
     * @param InvestigationService $investigationService
     */
    public function __construct(
        protected InvestigationService $investigationService
    ) {}

    /**
     * Display a listing of investigations assigned to the current user.
     * Route: GET /investigations
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // If the user has permission, let them list cases.
        if (!Gate::allows('view-investigasi')) {
            abort(403, 'Anda tidak memiliki wewenang untuk melihat modul investigasi.');
        }

        if ($user->hasRole('super-admin')) {
            // Super Admin can see all investigations
            $investigations = Investigation::with(['laporan.kategori', 'investigator'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // Investigator can only see their assigned investigations
            $investigations = Investigation::with(['laporan.kategori'])
                ->where('investigator_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('investigations.index', compact('investigations'));
    }

    /**
     * Display details of a specific investigation.
     * Route: GET /investigations/{id}
     */
    public function show($id)
    {
        $investigation = $this->investigationService->getInvestigationDetails($id);

        Gate::authorize('view', $investigation);

        return view('investigations.show', compact('investigation'));
    }

    /**
     * Add timeline update to the investigation.
     * Route: POST /investigations/{id}/timeline
     */
    public function storeTimeline(StoreTimelineRequest $request, $id)
    {
        $investigation = Investigation::findOrFail($id);

        Gate::authorize('update', $investigation);

        $this->investigationService->addTimeline(
            $id,
            $request->validated(),
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('investigations.show', $id)
            ->with('success', 'Timeline perkembangan berhasil ditambahkan.');
    }

    /**
     * Securely upload supporting document.
     * Route: POST /investigations/{id}/document
     */
    public function storeDocument(StoreDocumentRequest $request, $id)
    {
        $investigation = Investigation::findOrFail($id);

        Gate::authorize('update', $investigation);

        $this->investigationService->uploadDocument(
            $id,
            $request->file('document'),
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('investigations.show', $id)
            ->with('success', 'Dokumen pendukung berhasil diunggah secara aman.');
    }

    /**
     * Securely download an investigation document.
     * Route: GET /investigations/{id}/document/{docId}/download
     */
    public function downloadDocument($id, $docId)
    {
        $investigation = Investigation::findOrFail($id);

        Gate::authorize('downloadDocument', $investigation);

        $document = InvestigationDocument::where('investigation_id', $id)->findOrFail($docId);

        if (!Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        return Storage::disk('local')->download($document->file_path, $document->file_name);
    }

    /**
     * Submit final result and recommendations.
     * Route: POST /investigations/{id}/result
     */
    public function updateResult(UpdateInvestigationRequest $request, $id)
    {
        $investigation = Investigation::findOrFail($id);

        Gate::authorize('update', $investigation);

        $this->investigationService->submitFinalResult(
            $id,
            $request->validated(),
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('investigations.show', $id)
            ->with('success', 'Hasil investigasi akhir dan rekomendasi berhasil disimpan dan diserahkan.');
    }
}
