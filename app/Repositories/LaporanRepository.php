<?php

namespace App\Repositories;

use App\Models\Laporan;

class LaporanRepository extends BaseRepository
{
    public function __construct(Laporan $model)
    {
        parent::__construct($model);
    }

    /**
     * Generate nomor registrasi unik: WBS-YYYY-NNNNN
     */
    public function generateNomorRegistrasi(): string
    {
        $year  = now()->year;
        $prefix = "WBS-{$year}-";

        $last = Laporan::where('nomor_registrasi', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('nomor_registrasi');

        $lastNumber = $last ? (int) substr($last, -5) : 0;
        $newNumber  = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);

        return $prefix . $newNumber;
    }

    /**
     * Generate token unik untuk tracking tanpa login
     */
    public function generateTrackingToken(): string
    {
        do {
            $token = strtoupper(bin2hex(random_bytes(8)));
        } while (Laporan::where('tracking_token', $token)->exists());

        return $token;
    }

    /**
     * Cari laporan berdasarkan tracking token
     */
    public function findByToken(string $token): ?Laporan
    {
        return Laporan::with(['kategori', 'buktis'])
            ->where('tracking_token', $token)
            ->first();
    }

    /**
     * Cari laporan berdasarkan nomor registrasi
     */
    public function findByNomor(string $nomor): ?Laporan
    {
        return Laporan::with(['kategori', 'buktis'])
            ->where('nomor_registrasi', $nomor)
            ->first();
    }
}
