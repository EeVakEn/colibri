<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'video',
        'preview'
    ];

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
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
