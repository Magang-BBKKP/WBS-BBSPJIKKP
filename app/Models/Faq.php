<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    /**
     * Scope for active FAQs sorted by order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order')->orderBy('created_at', 'desc');
    }
}
