<?php

namespace App\Repositories\Contracts;

use App\Repositories\RepositoryInterface;
use App\Models\Laporan;
use Illuminate\Support\Collection;

interface LaporanRepositoryInterface extends RepositoryInterface
{
    /**
     * Generate nomor registrasi unik: WBS-YYYY-NNNNN
     */
    public function generateNomorRegistrasi(): string;

    /**
     * Generate token unik untuk tracking tanpa login
     */
    public function generateTrackingToken(): string;

    /**
     * Cari laporan berdasarkan tracking token
     */
    public function findByToken(string $token): ?Laporan;

    /**
     * Cari laporan berdasarkan nomor registrasi
     */
    public function findByNomor(string $nomor): ?Laporan;

    /**
     * Hitung total laporan berdasarkan status (bisa array atau string)
     */
    public function countByStatus(string|array $status): int;

    /**
     * Hitung total seluruh laporan
     */
    public function countAll(): int;

    /**
     * Ambil laporan terbaru dengan limit tertentu
     */
    public function getRecentLaporan(int $limit = 5): Collection;

    /**
     * Ambil data tren mingguan untuk chart (jumlah laporan per minggu)
     */
    public function getWeeklyTrends(int $weeks = 12): Collection;

    /**
     * Ambil data distribusi pelanggaran per kategori untuk chart
     */
    public function getCategoryDistribution(): Collection;
}
