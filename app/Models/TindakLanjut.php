<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TindakLanjut extends Model
{
    use HasFactory;

    protected $table = 'tindak_lanjuts';

    protected $fillable = [
        'laporan_id',
        'investigation_id',
        'jenis_tindakan',
        'keterangan',
        'ditetapkan_oleh',
        'ditetapkan_pada',
    ];

    protected $casts = [
        'ditetapkan_pada' => 'datetime',
    ];

    const JENIS = [
        'pembinaan'          => 'Pembinaan',
        'teguran'            => 'Teguran',
        'hukuman_disiplin'   => 'Hukuman Disiplin',
        'pemutusan_kontrak'  => 'Pemutusan Kontrak',
        'pelaporan_aph'      => 'Pelaporan ke APH',
        'perbaikan_sistem'   => 'Perbaikan Sistem',
    ];

    const JENIS_COLOR = [
        'pembinaan'          => 'info',
        'teguran'            => 'warning',
        'hukuman_disiplin'   => 'danger',
        'pemutusan_kontrak'  => 'dark',
        'pelaporan_aph'      => 'danger',
        'perbaikan_sistem'   => 'primary',
    ];

    public function getJenisLabelAttribute(): string
    {
        return self::JENIS[$this->jenis_tindakan] ?? $this->jenis_tindakan;
    }

    public function getJenisColorAttribute(): string
    {
        return self::JENIS_COLOR[$this->jenis_tindakan] ?? 'secondary';
    }

    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    public function investigation()
    {
        return $this->belongsTo(Investigation::class, 'investigation_id');
    }

    public function ditetapkanOleh()
    {
        return $this->belongsTo(User::class, 'ditetapkan_oleh');
    }
}
