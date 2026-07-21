<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'investigator_id',
        'assigned_by',
        'assigned_at',
        'status',
        'final_result',
        'recommendation',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Status Constants
    const STATUS_PENDING   = 'pending';
    const STATUS_ACTIVE    = 'active';
    const STATUS_COMPLETED = 'completed';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING   => ['label' => 'Menunggu Investigasi', 'color' => 'warning'],
            self::STATUS_ACTIVE    => ['label' => 'Sedang Berjalan',      'color' => 'primary'],
            self::STATUS_COMPLETED => ['label' => 'Selesai',              'color' => 'success'],
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::statusLabels()[$this->status]['color'] ?? 'secondary';
    }

    /**
     * Relationship with the Laporan.
     */
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    /**
     * Relationship with the assigned Investigator.
     */
    public function investigator()
    {
        return $this->belongsTo(User::class, 'investigator_id');
    }

    /**
     * Relationship with progress updates / timelines.
     */
    public function timelines()
    {
        return $this->hasMany(InvestigationTimeline::class, 'investigation_id')->orderBy('date', 'desc');
    }

    /**
     * Relationship with supporting documents.
     */
    public function documents()
    {
        return $this->hasMany(InvestigationDocument::class, 'investigation_id');
    }

    /**
     * Relationship with the user who assigned the investigator.
     */
    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Relationship with tindak lanjut.
     */
    public function tindakLanjut()
    {
        return $this->hasOne(TindakLanjut::class, 'investigation_id');
    }
}
