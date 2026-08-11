<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question'  => 'Apakah identitas saya benar-benar dirahasiakan?',
                'answer'    => 'Ya. Kami menyediakan opsi Pelaporan Anonim di mana Anda tidak perlu mengisi data diri Anda. Namun disarankan agar Anda memberikan kontak (email/no HP) untuk keperluan permintaan klarifikasi oleh Tim WBS tanpa mengungkap identitas Anda ke publik.',
                'is_active' => true,
                'order'     => 10,
            ],
            [
                'question'  => 'Berapa lama laporan saya akan diproses?',
                'answer'    => 'Laporan Anda akan segera diverifikasi oleh Tim WBS. Jika dinyatakan valid, Kepala BBSPJIKKP akan membentuk Tim Investigasi. Durasi proses tergantung pada kompleksitas pelanggaran dan bukti yang dilampirkan. Anda dapat terus memantau statusnya di halaman Track.',
                'is_active' => true,
                'order'     => 20,
            ],
            [
                'question'  => 'Bukti seperti apa yang harus saya unggah?',
                'answer'    => 'Anda dapat mengunggah dokumen (PDF, Word, Excel), Foto, maupun Video/Audio yang memperkuat laporan Anda. Sistem mendukung pengunggahan banyak file dengan ukuran maksimal tertentu yang telah ditentukan.',
                'is_active' => true,
                'order'     => 30,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question']],
                $faq
            );
        }
    }
}
