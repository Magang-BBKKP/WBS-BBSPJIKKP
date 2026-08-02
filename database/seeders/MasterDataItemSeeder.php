<?php

namespace Database\Seeders;

use App\Models\MasterDataItem;
use Illuminate\Database\Seeder;

class MasterDataItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'unit' => [
                ['name' => 'Tata Usaha', 'description' => 'Unit administrasi dan dukungan layanan internal.', 'sort_order' => 1],
                ['name' => 'Standardisasi dan Sertifikasi', 'description' => 'Unit layanan standardisasi, sertifikasi, dan penjaminan mutu.', 'sort_order' => 2],
                ['name' => 'Pengujian dan Kalibrasi', 'description' => 'Unit layanan pengujian, laboratorium, dan kalibrasi.', 'sort_order' => 3],
                ['name' => 'Pengembangan Jasa Industri', 'description' => 'Unit pengembangan layanan teknis dan jasa industri.', 'sort_order' => 4],
            ],
            'status' => [
                ['name' => 'Menunggu Verifikasi', 'description' => 'Laporan baru diterima dan menunggu pemeriksaan awal.', 'color' => '#f59e0b', 'sort_order' => 1],
                ['name' => 'Sedang Diverifikasi', 'description' => 'Tim WBS sedang memeriksa kelengkapan laporan.', 'color' => '#0ea5e9', 'sort_order' => 2],
                ['name' => 'Terverifikasi', 'description' => 'Laporan dinyatakan valid untuk ditindaklanjuti.', 'color' => '#2563eb', 'sort_order' => 3],
                ['name' => 'Dalam Investigasi', 'description' => 'Laporan sedang diproses oleh tim investigasi.', 'color' => '#334155', 'sort_order' => 4],
                ['name' => 'Ditolak', 'description' => 'Laporan tidak memenuhi kriteria atau bukti awal.', 'color' => '#dc2626', 'sort_order' => 5],
                ['name' => 'Selesai', 'description' => 'Seluruh proses tindak lanjut telah selesai.', 'color' => '#16a34a', 'sort_order' => 6],
            ],
            'prioritas' => [
                ['name' => 'Rendah', 'description' => 'Risiko terbatas dan tidak membutuhkan eskalasi segera.', 'color' => '#64748b', 'sort_order' => 1],
                ['name' => 'Sedang', 'description' => 'Perlu diproses sesuai antrian dan tenggat normal.', 'color' => '#2563eb', 'sort_order' => 2],
                ['name' => 'Tinggi', 'description' => 'Berdampak besar dan perlu perhatian lebih cepat.', 'color' => '#f97316', 'sort_order' => 3],
                ['name' => 'Kritis', 'description' => 'Berisiko tinggi terhadap integritas, keamanan, atau layanan.', 'color' => '#dc2626', 'sort_order' => 4],
            ],
        ];

        foreach ($items as $type => $rows) {
            foreach ($rows as $row) {
                MasterDataItem::firstOrCreate(
                    ['type' => $type, 'name' => $row['name']],
                    array_merge([
                        'description' => null,
                        'color' => null,
                        'is_active' => true,
                        'sort_order' => 0,
                    ], $row)
                );
            }
        }
    }
}
