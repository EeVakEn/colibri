<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends Model
{
    protected $fillable = [
        'amount',
        'channel_id',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('amount', '>', 0)
                ->whereDate('created_at', '>=', now()->subMonth())
                ->orWhere('amount', 0);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
