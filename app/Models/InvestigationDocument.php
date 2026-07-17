<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'investigation_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    /**
     * Relationship with parent investigation.
     */
    public function investigation()
    {
        return $this->belongsTo(Investigation::class, 'investigation_id');
    }

    /**
     * Relationship with the uploading user.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
