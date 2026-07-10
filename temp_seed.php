<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$l = \App\Models\Laporan::create([
    'nomor_registrasi' => 'WBS-TEST-123',
    'tracking_token' => 'ABCD-1234-EFGH-5678',
    'kategori_id' => 1,
    'judul' => 'Test Report',
    'deskripsi' => 'Test Description',
    'status' => 'menunggu',
    'is_anonim' => true,
]);

\App\Models\LaporanTimeline::create([
    'laporan_id' => $l->id,
    'status' => 'menunggu',
    'title' => 'Laporan Diterima',
    'description' => 'Laporan Anda telah diterima oleh sistem dan sedang menunggu verifikasi.'
]);

\App\Models\LaporanMessage::create([
    'laporan_id' => $l->id,
    'sender_type' => 'investigator',
    'content' => 'Terima kasih atas laporan Anda. Mohon sertakan bukti tambahan jika ada.'
]);

echo "Seeded tracking data.";
