<?php

namespace App\Repositories;

use App\Models\Laporan;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use Illuminate\Support\Collection;

class LaporanRepository extends BaseRepository implements LaporanRepositoryInterface
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

    /**
     * Hitung total laporan berdasarkan status (bisa array atau string)
     */
    public function countByStatus(string|array $status): int
    {
        if (is_array($status)) {
            return $this->model->whereIn('status', $status)->count();
        }
        return $this->model->where('status', $status)->count();
    }

    /**
     * Hitung total seluruh laporan
     */
    public function countAll(): int
    {
        return $this->model->count();
    }

    /**
     * Ambil laporan terbaru dengan limit tertentu
     */
    public function getRecentLaporan(int $limit = 5): Collection
    {
        return $this->model->with('kategori')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Ambil data tren bulanan untuk chart (jumlah laporan per bulan)
     */
    public function getMonthlyTrends(int $months = 12): Collection
    {
        $startDate = now()->subMonths($months - 1)->startOfMonth();

        return $this->model->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, count(*) as total")
            ->where('created_at', '>=', $startDate)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }

    /**
     * Ambil data distribusi pelanggaran per kategori untuk chart
     */
    public function getCategoryDistribution(): Collection
    {
        return $this->model->selectRaw('kategori_id, count(*) as total')
            ->with('kategori')
            ->groupBy('kategori_id')
            ->get();
    }
}
