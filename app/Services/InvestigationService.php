<?php

namespace App\Services;

use App\Models\Investigation;
use App\Models\InvestigationTimeline;
use App\Models\InvestigationDocument;
use App\Models\Laporan;
use App\Models\LaporanTimeline;
use App\Repositories\Contracts\InvestigationRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InvestigationService extends BaseService
{
    public function __construct(
        protected InvestigationRepositoryInterface $investigationRepository,
        protected AuditLogRepositoryInterface $auditLogRepository
    ) {}

    /**
     * Get investigations assigned to a specific investigator.
     */
    public function getInvestigatorInvestigations(int $investigatorId): Collection
    {
        return $this->investigationRepository->getByInvestigator($investigatorId);
    }

    /**
     * Get details of a single investigation.
     */
    public function getInvestigationDetails(int $id): Investigation
    {
        return $this->investigationRepository->findWithDetails($id);
    }

    /**
     * Add timeline entry to the investigation.
     */
    public function addTimeline(int $investigationId, array $data, int $userId, string $ip, string $userAgent): InvestigationTimeline
    {
        return DB::transaction(function () use ($investigationId, $data, $userId, $ip, $userAgent) {
            $investigation = $this->investigationRepository->find($investigationId);

            if ($investigation->status === Investigation::STATUS_COMPLETED) {
                throw ValidationException::withMessages([
                    'status' => 'Tidak dapat menambahkan timeline pada investigasi yang sudah selesai.'
                ]);
            }

            // Create InvestigationTimeline entry
            $timeline = InvestigationTimeline::create([
                'investigation_id' => $investigation->id,
                'description' => $data['description'],
                'date' => $data['date'],
            ]);

            // Auto-activate investigation if it was pending
            if ($investigation->status === Investigation::STATUS_PENDING) {
                $investigation->update(['status' => Investigation::STATUS_ACTIVE]);
            }

            // Sync with parent Laporan status and timelines if needed
            $laporan = $investigation->laporan;
            if ($laporan && $laporan->status !== Laporan::STATUS_INVESTIGASI) {
                $laporan->update(['status' => Laporan::STATUS_INVESTIGASI]);
            }

            // Add progress timeline for Laporan (Whistleblower tracking)
            LaporanTimeline::create([
                'laporan_id' => $laporan->id,
                'status' => Laporan::STATUS_INVESTIGASI,
                'title' => 'Update Investigasi',
                'description' => 'Pembaruan dari tim investigator: ' . $data['description'],
            ]);

            // Audit log
            $this->logActivity(
                $userId,
                'Tambah Timeline Investigasi',
                "Menambahkan update timeline pada investigasi laporan #{$laporan->nomor_registrasi}.",
                $ip,
                $userAgent
            );

            return $timeline;
        });
    }

    /**
     * Securely upload supporting document.
     */
    public function uploadDocument(int $investigationId, UploadedFile $file, int $userId, string $ip, string $userAgent): InvestigationDocument
    {
        return DB::transaction(function () use ($investigationId, $file, $userId, $ip, $userAgent) {
            $investigation = $this->investigationRepository->find($investigationId);

            if ($investigation->status === Investigation::STATUS_COMPLETED) {
                throw ValidationException::withMessages([
                    'status' => 'Tidak dapat mengunggah dokumen pada investigasi yang sudah selesai.'
                ]);
            }

            $originalName = $file->getClientOriginalName();
            $fileName     = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Secure upload: Stored in 'local' disk under private 'investigations' directory
            // This is NOT inside public/storage and not accessible via URL direct link
            $path = $file->storeAs('investigations/' . $investigation->id, $fileName, 'local');

            $document = InvestigationDocument::create([
                'investigation_id' => $investigation->id,
                'file_name' => $originalName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => $userId,
            ]);

            // Audit log
            $laporan = $investigation->laporan;
            $this->logActivity(
                $userId,
                'Upload Dokumen Investigasi',
                "Mengunggah dokumen '{$originalName}' pada investigasi laporan #{$laporan->nomor_registrasi}.",
                $ip,
                $userAgent
            );

            return $document;
        });
    }

    /**
     * Submit final result and recommendation.
     */
    public function submitFinalResult(int $investigationId, array $data, int $userId, string $ip, string $userAgent): Investigation
    {
        return DB::transaction(function () use ($investigationId, $data, $userId, $ip, $userAgent) {
            $investigation = $this->investigationRepository->find($investigationId);

            if ($investigation->status === Investigation::STATUS_COMPLETED) {
                throw ValidationException::withMessages([
                    'status' => 'Investigasi sudah diselesaikan sebelumnya.'
                ]);
            }

            // Update status, result, and recommendation
            $investigation->update([
                'status' => Investigation::STATUS_COMPLETED,
                'final_result' => $data['final_result'],
                'recommendation' => $data['recommendation'],
            ]);

            // Add final timeline entry for Investigation
            InvestigationTimeline::create([
                'investigation_id' => $investigation->id,
                'description' => 'Laporan hasil investigasi dan rekomendasi tindakan telah diserahkan kepada Pimpinan.',
                'date' => now(),
            ]);

            // Add timeline entry for Laporan (Whistleblower view)
            $laporan = $investigation->laporan;
            LaporanTimeline::create([
                'laporan_id' => $laporan->id,
                'status' => Laporan::STATUS_INVESTIGASI,
                'title' => 'Investigasi Selesai',
                'description' => 'Tim Investigator telah menyerahkan hasil investigasi dan rekomendasi kepada Kepala BBSPJIKKP.',
            ]);

            // Audit log
            $this->logActivity(
                $userId,
                'Submit Hasil Investigasi',
                "Menyerahkan hasil akhir dan rekomendasi investigasi untuk laporan #{$laporan->nomor_registrasi}.",
                $ip,
                $userAgent
            );

            return $investigation;
        });
    }

    /**
     * Log activity to audit logs.
     */
    protected function logActivity(?int $userId, string $action, string $description, string $ip, string $userAgent): void
    {
        $this->auditLogRepository->log([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }
}
