<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Laporan;

class LaporanStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(protected Laporan $laporan) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $statusLabel = $this->laporan->status_label;
        if ($this->laporan->verification_status === 'waiting_clarification') {
            $statusLabel = 'Menunggu Klarifikasi';
        } elseif ($this->laporan->verification_status === 'rejected') {
            $statusLabel = 'Ditolak';
        }

        $mailMessage = (new MailMessage)
            ->subject('Perubahan Status Laporan WBS #' . $this->laporan->nomor_registrasi)
            ->greeting('Halo, ' . ($this->laporan->nama_pelapor ?? 'Pelapor WBS'))
            ->line('Status laporan Anda dengan nomor registrasi **' . $this->laporan->nomor_registrasi . '** telah diperbarui menjadi: **' . $statusLabel . '**.');

        if ($this->laporan->verification_status === 'waiting_clarification') {
            $mailMessage->line('Tim WBS memerlukan klarifikasi atau keterangan tambahan berikut:')
                ->line('"' . $this->laporan->clarification_message . '"')
                ->line('Silakan berikan tanggapan Anda dengan mengakses portal pelacakan laporan menggunakan kode akses/token tracking Anda.')
                ->action('Berikan Klarifikasi', route('track.show', ['token' => $this->laporan->tracking_token]));
        } elseif ($this->laporan->verification_status === 'rejected') {
            $mailMessage->line('Alasan Penolakan: **' . $this->laporan->rejection_reason . '**');
            if ($this->laporan->verification_note) {
                $mailMessage->line('Catatan Tambahan: ' . $this->laporan->verification_note);
            }
        } else {
            if ($this->laporan->verification_note) {
                $mailMessage->line('Catatan Verifikasi: ' . $this->laporan->verification_note);
            }
            $mailMessage->action('Pantau Laporan', route('track.show', ['token' => $this->laporan->tracking_token]));
        }

        return $mailMessage
            ->line('Terima kasih atas partisipasi Anda dalam menjaga integritas dan profesionalitas di lingkungan BBSPJIKKP.')
            ->salutation('Salam, Tim WBS BBSPJIKKP');
    }
}
