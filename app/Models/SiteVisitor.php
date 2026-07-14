<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisitor extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'first_seen_at',
        'last_seen_at',
        'total_requests',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];
}
