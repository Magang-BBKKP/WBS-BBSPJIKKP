<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'action', 'description', 'ip_address', 'user_agent'])]
class AuditLog extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
