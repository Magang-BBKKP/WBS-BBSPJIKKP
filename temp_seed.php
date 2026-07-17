<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Laporan;
use App\Models\LaporanTimeline;
use App\Models\LaporanMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    // 1. Create Tim WBS User
    $wbsUser = User::firstOrCreate(
        ['email' => 'wbs@bbspjikkp.go.id'],
        [
            'name'      => 'Petugas Tim WBS',
            'password'  => Hash::make('Wbs@bbspjikkp123'),
            'is_active' => true,
            'status'    => 'active',
        ]
    );
    
    // Reset and sync role
    $wbsUser->syncRoles([]);
    $wbsUser->assignRole('tim-wbs');

    // 2. Create another test report that is 'menunggu' (Waiting Verification)
    $l1 = Laporan::firstOrCreate(
        ['nomor_registrasi' => 'WBS-2026-00001'],
        [
            'tracking_token'   => 'WBS-TOKEN-1111',
            'kategori_id'      => 1, // Korupsi
            'judul'            => 'Dugaan Gratifikasi Pembelian Alat Lab',
            'deskripsi'        => 'Terdapat indikasi penerimaan gratifikasi berupa barang elektronik oleh oknum panitia pengadaan alat lab pada awal bulan Juni 2026.',
            'status'           => 'menunggu',
            'is_anonim'        => false,
            'nama_pelapor'     => 'Ahmad Pelapor',
            'email_pelapor'    => 'ahmad@example.com',
            'telepon_pelapor'  => '08123456789',
            'tanggal_kejadian' => '2026-06-05',
            'lokasi'           => 'Gedung Standardisasi Lt. 2',
            'nama_terlapor'    => 'Budi Terlapor',
            'jabatan_terlapor' => 'Pranata Laboratorium',
            'unit_terlapor'    => 'Seksi Pengujian',
        ]
    );

    // Initial timeline
    $l1->timelines()->delete();
    LaporanTimeline::create([
        'laporan_id'  => $l1->id,
        'status'      => 'menunggu',
        'title'       => 'Laporan Diterima',
        'description' => 'Laporan Anda telah berhasil masuk ke sistem dan sedang mengantre untuk diverifikasi oleh Tim WBS.'
    ]);

    // 3. Create another test report that is waiting for clarification
    $l2 = Laporan::firstOrCreate(
        ['nomor_registrasi' => 'WBS-2026-00002'],
        [
            'tracking_token'   => 'WBS-TOKEN-2222',
            'kategori_id'      => 2, // Suap
            'judul'            => 'Permintaan Suap Uji Sertifikasi Produk',
            'deskripsi'        => 'Saya dimintai biaya tambahan di luar tarif resmi PNBP untuk mempercepat hasil uji sertifikasi karet ban.',
            'status'           => 'menunggu',
            'verification_status' => 'waiting_clarification',
            'clarification_message' => 'Mohon sebutkan nama oknum petugas sertifikasi yang meminta biaya tambahan tersebut.',
            'is_anonim'        => true,
            'tanggal_kejadian' => '2026-07-10',
            'lokasi'           => 'Loket Pelayanan Utama',
        ]
    );

    $l2->timelines()->delete();
    LaporanTimeline::create([
        'laporan_id'  => $l2->id,
        'status'      => 'menunggu',
        'title'       => 'Permintaan Klarifikasi',
        'description' => 'Petugas meminta klarifikasi tambahan terkait laporan Anda.'
    ]);

    $l2->messages()->delete();
    LaporanMessage::create([
        'laporan_id'  => $l2->id,
        'sender_type' => 'investigator',
        'content'     => '[PERMINTAAN KLARIFIKASI TIM WBS]: Mohon sebutkan nama oknum petugas sertifikasi yang meminta biaya tambahan tersebut.',
    ]);
});

echo "Temporary seeding completed successfully!\n";
echo "Tim WBS user: wbs@bbspjikkp.go.id / Wbs@bbspjikkp123\n";
