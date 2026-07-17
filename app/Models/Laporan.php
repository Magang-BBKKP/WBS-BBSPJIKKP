<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    protected $fillable = [
        'nomor_registrasi',
        'tracking_token',
        'kategori_id',
        'judul',
        'deskripsi',
        'tanggal_kejadian',
        'lokasi',
        'nama_terlapor',
        'jabatan_terlapor',
        'unit_terlapor',
        'is_anonim',
        'nama_pelapor',
        'email_pelapor',
        'telepon_pelapor',
        'status',
        'catatan_admin',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_note',
        'clarification_message',
        'rejection_reason',
    ];

    protected $casts = [
        'is_anonim'        => 'boolean',
        'tanggal_kejadian' => 'date',
        'verified_at'      => 'datetime',
    ];

    // Status constants
    const STATUS_MENUNGGU    = 'menunggu';
    const STATUS_VERIFIKASI  = 'verifikasi';
    const STATUS_VALID       = 'valid';
    const STATUS_DITOLAK     = 'ditolak';
    const STATUS_INVESTIGASI = 'investigasi';
    const STATUS_SELESAI     = 'selesai';

    public static function statusLabel(): array
    {
        return [
            self::STATUS_MENUNGGU    => ['label' => 'Menunggu Verifikasi', 'color' => 'warning'],
            self::STATUS_VERIFIKASI  => ['label' => 'Sedang Diverifikasi', 'color' => 'info'],
            self::STATUS_VALID       => ['label' => 'Terverifikasi',       'color' => 'primary'],
            self::STATUS_DITOLAK     => ['label' => 'Ditolak',             'color' => 'danger'],
            self::STATUS_INVESTIGASI => ['label' => 'Dalam Investigasi',   'color' => 'dark'],
            self::STATUS_SELESAI     => ['label' => 'Selesai',             'color' => 'success'],
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->verification_status === 'waiting_clarification') {
            return 'Menunggu Klarifikasi';
        }
        return self::statusLabel()[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        if ($this->verification_status === 'waiting_clarification') {
            return 'info';
        }
        return self::statusLabel()[$this->status]['color'] ?? 'secondary';
    }

    // Relations
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function buktis()
    {
        return $this->hasMany(Bukti::class, 'laporan_id');
    }

    public function timelines()
    {
        return $this->hasMany(LaporanTimeline::class, 'laporan_id');
    }

    public function messages()
    {
        return $this->hasMany(LaporanMessage::class, 'laporan_id');
    }
}
