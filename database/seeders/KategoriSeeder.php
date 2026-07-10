<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed 10 kategori pelanggaran sesuai PRD WBS BBSPJIKKP.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama'      => 'Korupsi',
                'deskripsi' => 'Penyalahgunaan jabatan atau wewenang untuk keuntungan pribadi atau kelompok.',
                'icon'      => 'bi-currency-exchange',
                'warna'     => '#dc2626',
                'urutan'    => 1,
            ],
            [
                'nama'      => 'Suap',
                'deskripsi' => 'Pemberian atau penerimaan sesuatu untuk mempengaruhi keputusan pejabat.',
                'icon'      => 'bi-cash-coin',
                'warna'     => '#ea580c',
                'urutan'    => 2,
            ],
            [
                'nama'      => 'Gratifikasi',
                'deskripsi' => 'Penerimaan hadiah atau imbalan yang berhubungan dengan jabatan.',
                'icon'      => 'bi-gift',
                'warna'     => '#d97706',
                'urutan'    => 3,
            ],
            [
                'nama'      => 'Benturan Kepentingan',
                'deskripsi' => 'Situasi di mana kepentingan pribadi mempengaruhi keputusan jabatan.',
                'icon'      => 'bi-people',
                'warna'     => '#7c3aed',
                'urutan'    => 4,
            ],
            [
                'nama'      => 'Kecurangan',
                'deskripsi' => 'Tindakan tidak jujur untuk memperoleh keuntungan yang tidak semestinya.',
                'icon'      => 'bi-exclamation-triangle',
                'warna'     => '#2563eb',
                'urutan'    => 5,
            ],
            [
                'nama'      => 'Pencurian',
                'deskripsi' => 'Pengambilan aset atau properti instansi tanpa izin yang sah.',
                'icon'      => 'bi-shield-exclamation',
                'warna'     => '#0f766e',
                'urutan'    => 6,
            ],
            [
                'nama'      => 'Pembocoran Data',
                'deskripsi' => 'Pengungkapan informasi rahasia instansi kepada pihak yang tidak berwenang.',
                'icon'      => 'bi-database-exclamation',
                'warna'     => '#1d4ed8',
                'urutan'    => 7,
            ],
            [
                'nama'      => 'Pelanggaran Hukum',
                'deskripsi' => 'Tindakan yang melanggar peraturan perundang-undangan yang berlaku.',
                'icon'      => 'bi-book',
                'warna'     => '#be123c',
                'urutan'    => 8,
            ],
            [
                'nama'      => 'Pelanggaran Akuntansi',
                'deskripsi' => 'Manipulasi atau pemalsuan laporan keuangan dan catatan akuntansi.',
                'icon'      => 'bi-calculator',
                'warna'     => '#0369a1',
                'urutan'    => 9,
            ],
            [
                'nama'      => 'Pelanggaran Etika',
                'deskripsi' => 'Perilaku yang bertentangan dengan kode etik dan norma profesi.',
                'icon'      => 'bi-person-x',
                'warna'     => '#475569',
                'urutan'    => 10,
            ],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::firstOrCreate(
                ['nama' => $kategori['nama']],
                array_merge($kategori, ['is_active' => true])
            );
        }

        $this->command->info('✓ 10 Kategori pelanggaran berhasil di-seed.');
    }
}
