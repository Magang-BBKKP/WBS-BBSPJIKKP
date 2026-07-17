<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigationTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'investigation_id',
        'description',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Relationship with parent investigation.
     */
    public function investigation()
    {
        return $this->belongsTo(Investigation::class, 'investigation_id');
    }
}
