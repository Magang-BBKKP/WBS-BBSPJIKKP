<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationTimelineEvidence extends Model
{
    use HasFactory;

    protected $table = 'investigation_timeline_evidences';

    protected $fillable = [
        'investigation_timeline_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    /**
     * Relationship with the parent timeline entry.
     */
    public function timeline()
    {
        return $this->belongsTo(InvestigationTimeline::class, 'investigation_timeline_id');
    }

    /**
     * Relationship with the uploading user.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
