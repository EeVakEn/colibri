<?php

namespace App\Models;

use Illuminate\Container\Attributes\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Channel extends Model
{
    public $guarded = [];

    protected $appends = ['is_subscribed', 'subs_count'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->subscriptions()->active();
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    public function getIsSubscribedAttribute(): bool
    {
        return $this->subscriptions()->active()->where('user_id', auth()->id())->exists() ?? false;
    }
    public function getSubsCountAttribute(): int
    {
        return $this->activeSubscriptions()->count() ?? 0;
    }

    public function views(): HasManyThrough
    {
        return $this->hasManyThrough(View::class, Content::class);
    }

}
