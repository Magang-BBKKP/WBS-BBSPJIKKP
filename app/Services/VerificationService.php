<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\LaporanMessage;
use App\Models\LaporanTimeline;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use App\Repositories\Contracts\AuditLogRepositoryInterface;
use App\Notifications\LaporanStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class VerificationService extends BaseService
{
    public function __construct(
        protected LaporanRepositoryInterface $laporanRepository,
        protected AuditLogRepositoryInterface $auditLogRepository
    ) {}

    /**
     * Ambil laporan untuk verifikasi dan ubah status ke UNDER_VERIFICATION (verifikasi) jika baru pertama kali dibuka.
     */
    public function getLaporanForVerification(int $id): Laporan
    {
        return DB::transaction(function () use ($id) {
            $laporan = $this->laporanRepository->find($id);

            // Jika status masih baru (menunggu / SUBMITTED), ubah ke sedang diverifikasi (UNDER_VERIFICATION)
            if ($laporan->status === Laporan::STATUS_MENUNGGU && $laporan->verification_status !== 'waiting_clarification') {
                $laporan->update([
                    'status' => Laporan::STATUS_VERIFIKASI,
                    'verification_status' => 'under_verification',
                ]);

                // Tambahkan Timeline
                LaporanTimeline::create([
                    'laporan_id' => $laporan->id,
                    'status' => Laporan::STATUS_VERIFIKASI,
                    'title' => 'Laporan Ditinjau',
                    'description' => 'Laporan sedang ditinjau oleh Tim WBS untuk verifikasi awal.',
                ]);

                // Log Activity
                $this->logActivity(
                    auth()->id(),
                    'Review Laporan',
                    "Tim WBS mulai meninjau laporan #{$laporan->nomor_registrasi}.",
                    request()->ip(),
                    request()->userAgent()
                );
            }

            return $laporan;
        });
    }

    /**
     * Aksi A: Validasi Laporan (Verifikasi)
     */
    public function validateReport(int $id, array $data): Laporan
    {
        return DB::transaction(function () use ($id, $data) {
            $laporan = $this->laporanRepository->find($id);

            // Validasi status
            $this->validateState($laporan);
            if ($laporan->status === Laporan::STATUS_DITOLAK || $laporan->verification_status === 'rejected') {
                throw ValidationException::withMessages([
                    'status' => 'Tidak boleh memvalidasi laporan yang sudah ditolak.'
                ]);
            }

            // Update status ke Terverifikasi (valid)
            $laporan->update([
                'status' => Laporan::STATUS_VALID,
                'verification_status' => 'verified',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'verification_note' => $data['verification_note'] ?? null,
            ]);

            // Tambah timeline
            LaporanTimeline::create([
                'laporan_id' => $laporan->id,
                'status' => Laporan::STATUS_VALID,
                'title' => 'Laporan Terverifikasi',
                'description' => 'Laporan Anda dinyatakan valid oleh Tim WBS dan siap diteruskan ke tahap investigasi.',
            ]);

            // Log activity
            $this->logActivity(
                auth()->id(),
                'Validasi Laporan',
                "Tim WBS memverifikasi laporan #{$laporan->nomor_registrasi} sebagai valid.",
                request()->ip(),
                request()->userAgent()
            );

            // Kirim notifikasi ke pelapor
            $this->sendNotification($laporan);

            return $laporan;
        });
    }

    /**
     * Aksi B: Meminta Klarifikasi
     */
    public function clarifyReport(int $id, array $data): Laporan
    {
        return DB::transaction(function () use ($id, $data) {
            $laporan = $this->laporanRepository->find($id);

            // Validasi status
            $this->validateState($laporan);
            if ($laporan->status === Laporan::STATUS_VALID || $laporan->verification_status === 'verified') {
                throw ValidationException::withMessages([
                    'status' => 'Tidak boleh meminta klarifikasi pada laporan yang sudah diverifikasi.'
                ]);
            }

            // Update status ke WAITING_CLARIFICATION (status di DB diparkir di 'menunggu' agar nanti setelah dijawab pelapor kembali 'menunggu')
            $laporan->update([
                'status' => Laporan::STATUS_MENUNGGU,
                'verification_status' => 'waiting_clarification',
                'clarification_message' => $data['clarification_message'],
            ]);

            // Kirim pesan ke secure chat channel
            LaporanMessage::create([
                'laporan_id' => $laporan->id,
                'sender_type' => 'investigator',
                'user_id' => auth()->id(),
                'content' => '[PERMINTAAN KLARIFIKASI TIM WBS]: ' . $data['clarification_message'],
            ]);

            // Tambah timeline
            LaporanTimeline::create([
                'laporan_id' => $laporan->id,
                'status' => Laporan::STATUS_MENUNGGU,
                'title' => 'Permintaan Klarifikasi',
                'description' => 'Tim WBS mengirimkan permintaan klarifikasi kepada pelapor terkait isi aduan.',
            ]);

            // Log activity
            $this->logActivity(
                auth()->id(),
                'Minta Klarifikasi',
                "Tim WBS meminta klarifikasi untuk laporan #{$laporan->nomor_registrasi}.",
                request()->ip(),
                request()->userAgent()
            );

            // Kirim notifikasi ke pelapor
            $this->sendNotification($laporan);

            return $laporan;
        });
    }

    /**
     * Aksi C: Menolak Laporan
     */
    public function rejectReport(int $id, array $data): Laporan
    {
        return DB::transaction(function () use ($id, $data) {
            $laporan = $this->laporanRepository->find($id);

            // Validasi status
            $this->validateState($laporan);

            // Update status ke Ditolak (rejected)
            $laporan->update([
                'status' => Laporan::STATUS_DITOLAK,
                'verification_status' => 'rejected',
                'rejection_reason' => $data['rejection_reason'],
                'verification_note' => $data['verification_note'] ?? null,
            ]);

            // Tambah timeline
            LaporanTimeline::create([
                'laporan_id' => $laporan->id,
                'status' => Laporan::STATUS_DITOLAK,
                'title' => 'Laporan Ditolak',
                'description' => 'Laporan Anda ditolak dengan alasan: ' . $data['rejection_reason'],
            ]);

            // Log activity
            $this->logActivity(
                auth()->id(),
                'Tolak Laporan',
                "Tim WBS menolak laporan #{$laporan->nomor_registrasi} karena: {$data['rejection_reason']}.",
                request()->ip(),
                request()->userAgent()
            );

            // Kirim notifikasi ke pelapor
            $this->sendNotification($laporan);

            return $laporan;
        });
    }

    /**
     * Validasi umum state laporan sebelum diubah
     */
    protected function validateState(Laporan $laporan): void
    {
        if ($laporan->status === Laporan::STATUS_SELESAI) {
            throw ValidationException::withMessages([
                'status' => 'Tidak boleh mengubah status jika laporan sudah selesai.'
            ]);
        }
    }

    /**
     * Helper log activity ke audit logs
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

    /**
     * Kirim notifikasi email ke pelapor non-anonim
     */
    protected function sendNotification(Laporan $laporan): void
    {
        if (!$laporan->is_anonim && $laporan->email_pelapor) {
            try {
                Notification::route('mail', $laporan->email_pelapor)
                    ->notify(new LaporanStatusUpdated($laporan));
            } catch (\Exception $e) {
                // Biarkan error notifikasi tidak membatalkan transaksi utama,
                // catat log error di laravel.log
                logger()->error('Gagal mengirim email notifikasi WBS: ' . $e->getMessage());
            }
        }
    }
}
