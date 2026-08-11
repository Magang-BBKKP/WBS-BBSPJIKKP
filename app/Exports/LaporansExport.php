<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporansExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $status;
    protected $kategoriId;
    protected $year;

    /**
     * LaporansExport constructor.
     *
     * @param string|null $status
     * @param int|null $kategoriId
     * @param int|null $year
     */
    public function __construct($status = null, $kategoriId = null, $year = null)
    {
        $this->status     = $status;
        $this->kategoriId = $kategoriId;
        $this->year       = $year;
    }

    /**
     * Query to fetch filtered reports.
     */
    public function query()
    {
        $query = Laporan::with(['kategori', 'investigation.tindakLanjut']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->kategoriId) {
            $query->where('kategori_id', $this->kategoriId);
        }

        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Header row for Excel.
     */
    public function headings(): array
    {
        return [
            'Nomor Registrasi',
            'Kategori',
            'Judul Laporan',
            'Deskripsi',
            'Tanggal Kejadian',
            'Lokasi',
            'Nama Terlapor',
            'Jabatan Terlapor',
            'Unit Terlapor',
            'Status',
            'Tindak Lanjut',
            'Tanggal Dibuat',
        ];
    }

    /**
     * Map each row of data.
     *
     * @param Laporan $laporan
     */
    public function map($laporan): array
    {
        return [
            $laporan->nomor_registrasi,
            $laporan->kategori->nama ?? '-',
            $laporan->judul,
            $laporan->deskripsi,
            $laporan->tanggal_kejadian ? $laporan->tanggal_kejadian->format('Y-m-d') : '-',
            $laporan->lokasi ?? '-',
            $laporan->nama_terlapor ?? '-',
            $laporan->jabatan_terlapor ?? '-',
            $laporan->unit_terlapor ?? '-',
            $laporan->status_label,
            $laporan->investigation->tindakLanjut->jenis_label ?? '-',
            $laporan->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
