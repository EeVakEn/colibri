<?php

namespace App\Models;

use App\Services\ContentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Laravel\Scout\Searchable;

class Content extends Model
{
    use Searchable;

    protected $fillable = [
        'title',
        'content',
        'type',
        'video',
        'preview',
        'processed_at',
        'processing_error',
        'duration',
    ];

    const TYPE_VIDEO = 'video';
    const TYPE_ARTICLE = 'article';

    const TYPES = [
        self::TYPE_VIDEO,
        self::TYPE_ARTICLE,
    ];

    protected $appends = ['video_url', 'preview_url', 'views_count'];

    protected static function booted(): void
    {
        static::saving(function ($content) {
            if (request()->hasFile('preview')) {
                if ($content->getOriginal('preview')) {
                    Storage::disk('public')->delete($content->getOriginal('preview'));
                }
                $content->preview = request()->file('preview')->store('previews', 'public');
            }

            if (request()->hasFile('video')) {
                if ($content->getOriginal('video')) {
                    Storage::disk('public')->delete($content->getOriginal('video'));
                }
                $videoFile = request()->file('video');
                $info = ContentService::saveVideo($videoFile);
                $content->duration = $info['duration'];
                $content->video = $info['path'];
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

    public function activeSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)->wherePivotNotNull('activated_at')->withPivot('depth', 'activated_at');
    }

    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getViewsCountAttribute(): ?int
    {
        return $this->views()->count();
    }

    public function contentSkills()
    {
        return $this->hasMany(ContentSkill::class, 'content_id');
    }

    public function searchableAs(): string
    {
        return 'content_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => (string)$this->id, // to connect with typesense
            'title' => $this->title,
            'content' => $this->content,
            'preview_url' => $this->preview_url,
            'views_count' => $this->views_count,
            'transcript' => $this->transcript?->text ?? '',
            'date' => $this->created_at,
            'created_at' => $this->created_at->timestamp,
        ];
    }
}
