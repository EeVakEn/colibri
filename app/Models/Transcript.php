<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transcript extends Model
{
    protected $fillable = ['text'];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
