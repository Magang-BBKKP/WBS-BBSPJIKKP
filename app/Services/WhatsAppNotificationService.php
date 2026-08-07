<?php

namespace App\Services;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class WhatsAppNotificationService
{
    public function sendStatusUpdate(Laporan $laporan): void
    {
        if (!config('services.whatsapp.enabled', true)) {
            return;
        }

        $phoneNumber = $this->normalizePhoneNumber($laporan->real_telepon_pelapor);

        if ($phoneNumber === null) {
            return;
        }

        $this->sendToPhone($phoneNumber, $this->buildStatusMessage($laporan));
    }

    public function notifyKepalaNewReport(Laporan $laporan): void
    {
        if (!config('services.whatsapp.enabled', true)) {
            return;
        }

        $phoneNumber = $this->normalizePhoneNumber(config('services.whatsapp.kepala_phone'));

        if ($phoneNumber === null) {
            return;
        }

        $this->sendToPhone($phoneNumber, $this->buildNewReportMessage($laporan));
    }

    public function notifyPelaporTrackingCode(Laporan $laporan): void
    {
        if (!config('services.whatsapp.enabled', true)) {
            return;
        }

        $phoneNumber = $this->normalizePhoneNumber($laporan->real_telepon_pelapor);

        if ($phoneNumber === null) {
            return;
        }

        $this->sendToPhone($phoneNumber, $this->buildTrackingCodeMessage($laporan));
    }

    public function notifyInvestigatorAssigned(Laporan $laporan, ?User $investigator): void
    {
        if (!config('services.whatsapp.enabled', true)) {
            return;
        }

        $phoneNumber = $this->normalizePhoneNumber($investigator?->phone_number)
            ?? $this->normalizePhoneNumber(config('services.whatsapp.investigator_fallback_phone'));

        if ($phoneNumber === null) {
            return;
        }

        $this->sendToPhone($phoneNumber, $this->buildAssignedMessage($laporan, $investigator));
    }

    protected function sendToPhone(string $phoneNumber, string $message): void
    {
        try {
            $request = Http::baseUrl(rtrim((string) config('services.whatsapp.base_url', 'http://localhost:3000'), '/'))
                ->acceptJson()
                ->asJson()
                ->timeout((int) config('services.whatsapp.timeout', 10));

            $deviceId = config('services.whatsapp.device_id');

            if (!empty($deviceId)) {
                $request = $request->withHeaders([
                    'X-Device-Id' => $deviceId,
                ]);
            }

            $response = $request->post('/send/message', [
                'phone' => $phoneNumber,
                'message' => $message,
            ]);

            if (! $response->successful()) {
                logger()->warning('Gagal mengirim notifikasi WhatsApp WBS.', [
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $exception) {
            logger()->error('Gagal mengirim notifikasi WhatsApp WBS: ' . $exception->getMessage(), [
                'phone' => $phoneNumber,
            ]);
        }
    }

    protected function buildNewReportMessage(Laporan $laporan): string
    {
        return implode(PHP_EOL . PHP_EOL, [
            'Kepada Kepala Balai,',
            'Ada laporan WBS baru diterima di sistem.',
            'Nomor Registrasi: #' . $laporan->nomor_registrasi,
            'Judul: ' . ($laporan->judul ?: '-'),
            'Kategori: ' . ($laporan->kategori->nama ?? '-'),
            'Silakan tinjau laporan tersebut di dashboard WBS.',
        ]);
    }

    protected function buildTrackingCodeMessage(Laporan $laporan): string
    {
        return implode(PHP_EOL . PHP_EOL, [
            'Halo, ' . ($laporan->real_nama_pelapor ?: 'Pelapor WBS') . '.',
            'Laporan Anda telah berhasil dikirim ke sistem WBS BBSPJIKKP.',
            'Nomor Registrasi: #' . $laporan->nomor_registrasi,
            'Kode Akses (Unique Access Code): ' . $laporan->tracking_token,
            'Simpan kode akses ini karena dibutuhkan untuk melacak status laporan Anda: ' . route('track.show', ['token' => $laporan->tracking_token]),
            'Jangan bagikan kode akses ini kepada siapa pun.',
        ]);
    }

    protected function buildAssignedMessage(Laporan $laporan, ?User $investigator): string
    {
        $nama = $investigator?->name ?: 'Investigator';

        return implode(PHP_EOL . PHP_EOL, [
            'Halo, ' . $nama . '.',
            'Anda ditugaskan sebagai investigator untuk laporan WBS #' . $laporan->nomor_registrasi . '.',
            'Judul: ' . ($laporan->judul ?: '-'),
            'Silakan buka sistem WBS untuk melihat detail investigasi.',
        ]);
    }

    protected function buildStatusMessage(Laporan $laporan): string
    {
        $statusLabel = $laporan->status_label;

        $lines = [
            'Halo, ' . ($laporan->real_nama_pelapor ?: 'Pelapor WBS') . '.',
            'Status laporan WBS #' . $laporan->nomor_registrasi . ' telah diperbarui menjadi: ' . $statusLabel . '.',
        ];

        if ($laporan->verification_status === 'waiting_clarification' && !empty($laporan->clarification_message)) {
            $lines[] = 'Tim WBS memerlukan klarifikasi berikut: ' . $laporan->clarification_message;
        } elseif ($laporan->verification_status === 'rejected' && !empty($laporan->rejection_reason)) {
            $lines[] = 'Alasan penolakan: ' . $laporan->rejection_reason;
        } elseif (!empty($laporan->verification_note)) {
            $lines[] = 'Catatan verifikasi: ' . $laporan->verification_note;
        }

        $lines[] = 'Silakan pantau status laporan di portal tracking: ' . route('track.show', ['token' => $laporan->tracking_token]);

        return implode(PHP_EOL . PHP_EOL, $lines);
    }

    protected function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) {
            return null;
        }

        $normalized = preg_replace('/\D+/', '', $phoneNumber);

        if ($normalized === null || $normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, '0')) {
            $normalized = '62' . substr($normalized, 1);
        } elseif (str_starts_with($normalized, '8')) {
            $normalized = '62' . $normalized;
        }

        return $normalized;
    }
}