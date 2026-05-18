<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';

    protected $fillable = [
        'email',
        'status',
        'confirm_token',
        'confirmed_at',
        'unsubscribed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(NewsletterLog::class, 'subscriber_id');
    }

    public function markPending(): void
    {
        $this->status = self::STATUS_PENDING;
        $this->confirm_token = Str::random(48);
        $this->confirmed_at = null;
        $this->unsubscribed_at = null;
    }

    public function markActive(): void
    {
        $this->status = self::STATUS_ACTIVE;
        $this->confirm_token = null;
        $this->confirmed_at = now();
        $this->unsubscribed_at = null;
    }

    public function markUnsubscribed(): void
    {
        $this->status = self::STATUS_UNSUBSCRIBED;
        $this->confirm_token = null;
        $this->unsubscribed_at = now();
    }
}
