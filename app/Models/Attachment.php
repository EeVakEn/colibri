<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
class Attachment extends Model
{
    protected $fillable = ['path', 'filename', 'mime_type', 'size'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($attachment) {
            if ($attachment->path) {
                Storage::disk('public')->delete($attachment->path);
            }
        });
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }


    public static function upload(UploadedFile $file, ?string $filename = null): static
    {
        $mimeType = $file->getMimeType();
        $path = 'attachments/' . uniqid() . '.' . $file->getClientOriginalExtension();
        if (str_contains($mimeType, 'image')) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            $encodedImage = $image->toWebp(80);
            Storage::disk('public')->put($path, $encodedImage->toString());
        } else {
            Storage::disk('public')->put($path, $file);
        }
        $size = Storage::disk('public')->size($path);
        return self::create([
            'path' => $path,
            'filename' => $filename ?? $file->getClientOriginalName(),
            'size' => $size,
            'mime_type' => $mimeType,
        ]);
    }

    public function getUrlAttribute(): ?string
    {
        if (Storage::disk('public')->exists($this->path)) {
            return Storage::disk('public')->url($this->path);
        }

        return null;
    }
}
