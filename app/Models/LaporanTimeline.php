<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'status',
        'title',
        'description',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
