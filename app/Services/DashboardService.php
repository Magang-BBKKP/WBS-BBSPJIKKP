<?php

namespace App\Services;

use App\Models\Laporan;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardService extends BaseService
{
    public function __construct(
        protected LaporanRepositoryInterface $laporanRepository
    ) {}

    /**
     * Mengambil seluruh data yang dibutuhkan untuk dashboard
     */
    public function getDashboardData(): array
    {
        return [
            'stats' => $this->getCardStats(),
            'recent_reports' => $this->getRecentReports(),
            'weekly_trends' => $this->getWeeklyTrendData(),
            'category_distribution' => $this->getCategoryDistributionData(),
            'progress' => $this->getProgressMetrics(),
        ];
    }

    /**
     * Mengambil statistik untuk card summary
     */
    protected function getCardStats(): array
    {
        return [
            'total' => $this->laporanRepository->countAll(),
            'menunggu' => $this->laporanRepository->countByStatus(Laporan::STATUS_MENUNGGU),
            'investigasi' => $this->laporanRepository->countByStatus(Laporan::STATUS_INVESTIGASI),
            'selesai' => $this->laporanRepository->countByStatus(Laporan::STATUS_SELESAI),
        ];
    }

    /**
     * Mengambil 5 laporan terbaru
     */
    protected function getRecentReports(): Collection
    {
        return $this->laporanRepository->getRecentLaporan(5);
    }

    /**
     * Mengambil data tren mingguan untuk chart (jumlah laporan per minggu)
     * Melakukan padding untuk minggu yang tidak memiliki data (0 laporan)
     */
    protected function getWeeklyTrendData(): array
    {
        $trends = $this->laporanRepository->getWeeklyTrends(12)->pluck('total', 'week')->toArray();
        $labels = [];
        $data = [];

        // Generasikan 12 minggu ke belakang dari minggu ini
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subWeeks($i)->startOfWeek();
            $weekKey = $date->format('Y-m-d'); // Format pembanding database (Monday of the week)
            $weekLabel = $date->translatedFormat('d M'); // Label visual (e.g., "20 Jul")

            $labels[] = $weekLabel;
            $data[] = $trends[$weekKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Mengambil data distribusi kategori untuk Pie/Doughnut Chart
     */
    protected function getCategoryDistributionData(): array
    {
        $distribution = $this->laporanRepository->getCategoryDistribution();
        $labels = [];
        $data = [];
        $colors = [];

        // Daftar warna fallback jika kategori tidak memiliki warna spesifik
        $fallbackColors = [
            '#0a4282', '#00d2ff', '#0b192c', '#6c757d', '#ffc107',
            '#dc3545', '#198754', '#0dcaf0', '#6f42c1', '#d63384'
        ];

        foreach ($distribution as $index => $item) {
            $labels[] = $item->kategori->nama ?? 'Lainnya';
            $data[] = $item->total;
            $colors[] = $item->kategori->warna ?? ($fallbackColors[$index % count($fallbackColors)]);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
        ];
    }

    /**
     * Menghitung metrik kemajuan investigasi untuk progress bar
     */
    protected function getProgressMetrics(): array
    {
        $total = $this->laporanRepository->countAll();
        $investigasi = $this->laporanRepository->countByStatus(Laporan::STATUS_INVESTIGASI);
        $selesai = $this->laporanRepository->countByStatus(Laporan::STATUS_SELESAI);

        $pctInvestigasi = $total > 0 ? round(($investigasi / $total) * 100) : 0;
        $pctSelesai = $total > 0 ? round(($selesai / $total) * 100) : 0;

        return [
            'total' => $total,
            'investigasi_count' => $investigasi,
            'investigasi_percentage' => $pctInvestigasi,
            'selesai_count' => $selesai,
            'selesai_percentage' => $pctSelesai,
        ];
    }
}
