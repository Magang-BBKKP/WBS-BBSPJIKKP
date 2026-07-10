<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'sender_type', // 'pelapor' or 'investigator'
        'user_id', // if investigator
        'content',
        'attachment_path',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
