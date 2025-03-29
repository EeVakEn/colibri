<?php

namespace App\Models;

use App\Services\ContentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'video',
        'preview',
        'processed_at',
    ];

    const TYPE_VIDEO = 'video';
    const TYPE_ARTICLE = 'article';

    protected $appends = ['video_url', 'preview_url'];

    protected static function booted()
    {
        static::saving(function ($content) {
            // Удаление и загрузка нового превью
            if (request()->hasFile('preview')) {
                if ($content->getOriginal('preview')) {
                    Storage::disk('public')->delete($content->getOriginal('preview'));
                }
                $content->preview = request()->file('preview')->store('previews', 'public');
            }

            // Удаление и загрузка нового видео
            if (request()->hasFile('video')) {
                if ($content->getOriginal('video')) {
                    Storage::disk('public')->delete($content->getOriginal('video'));
                }
                $content->video = request()->file('video')->store('videos', 'public');
            }
        });

        static::deleted(function ($content) {
            if ($content->video) {
                Storage::disk('public')->delete($content->video);
            }
            if ($content->preview) {
                Storage::disk('public')->delete($content->preview);
            }
        });
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function getVideoUrlAttribute(): ?string
    {
        return Storage::url($this->video) ?? null;
    }


    public function getPreviewUrlAttribute(): ?string
    {
        return Storage::url($this->preview) ?? null;
    }

    public function getSimilarAttribute(): Collection
    {
        return Content::where('type', $this->type)->with('channel.user')->get();
    }

    public function getPathAttribute(): ?string
    {
        return Storage::disk('public')->path($this->video) ?? null;
    }

    public function transcript()
    {
        return $this->hasOne(Transcript::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->withPivot('depth', 'activated_at');
    }

}
