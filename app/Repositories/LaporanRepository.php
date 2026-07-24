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
     * Generate nomor registrasi unik: WBS-XXXX-XXXX (random alphanumeric)
     */
    public function generateNomorRegistrasi(): string
    {
        do {
            $part1 = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $part2 = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4));
            $nomor = "WBS-{$part1}-{$part2}";
        } while (Laporan::where('nomor_registrasi', $nomor)->exists());

        return $nomor;
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
     * Ambil data tren mingguan untuk chart (jumlah laporan per minggu)
     */
    public function getWeeklyTrends(int $weeks = 12): Collection
    {
        $startDate = now()->subWeeks($weeks - 1)->startOfWeek();
        $driver = $this->model->getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return $this->model->selectRaw("date(created_at, 'weekday 0', '-6 days') as week, count(*) as total")
                ->where('created_at', '>=', $startDate)
                ->groupBy('week')
                ->orderBy('week', 'asc')
                ->get();
        }

        return $this->model->selectRaw("DATE_FORMAT(DATE_SUB(created_at, INTERVAL WEEKDAY(created_at) DAY), '%Y-%m-%d') as week, count(*) as total")
            ->where('created_at', '>=', $startDate)
            ->groupBy('week')
            ->orderBy('week', 'asc')
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
