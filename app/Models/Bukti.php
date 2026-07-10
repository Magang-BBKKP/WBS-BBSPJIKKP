<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Bukti extends Model
{
    use HasFactory;

    protected $table = 'buktis';

    protected $fillable = [
        'laporan_id',
        'nama_asli',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran',
    ];

    // Relations
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_id');
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return Storage::url($this->path_file);
    }

    public function getUkuranFormatAttribute(): string
    {
        $bytes = $this->ukuran;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }
}
