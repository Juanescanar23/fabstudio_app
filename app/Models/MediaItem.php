<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MediaItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'collection',
        'alt_text',
        'caption',
        'file_path',
        'disk',
        'external_url',
        'mime_type',
        'size',
        'is_public',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_public', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function publicUrl(): ?string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if (! $this->file_path) {
            return null;
        }

        return Storage::disk($this->disk ?: 'public')->url($this->file_path);
    }
}
