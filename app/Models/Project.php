<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'lead_id',
        'code',
        'name',
        'typology',
        'status',
        'current_phase',
        'location',
        'description',
        'budget_estimate',
        'starts_at',
        'ends_at',
        'public_slug',
        'public_summary',
        'public_cover_path',
        'is_public',
        'is_featured',
        'public_published_at',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'budget_estimate' => 'decimal:2',
            'starts_at' => 'date',
            'ends_at' => 'date',
            'is_public' => 'boolean',
            'is_featured' => 'boolean',
            'public_published_at' => 'datetime',
        ];
    }

    public function scopePublic($query)
    {
        return $query
            ->where('is_public', true)
            ->whereNotNull('public_published_at')
            ->where('public_published_at', '<=', now());
    }

    public function publicCoverUrl(): ?string
    {
        if (! $this->public_cover_path) {
            return null;
        }

        if (str_starts_with($this->public_cover_path, 'http://') || str_starts_with($this->public_cover_path, 'https://')) {
            return $this->public_cover_path;
        }

        return Storage::disk('public')->url($this->public_cover_path);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(ProjectPhase::class)->orderBy('sort_order');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProjectDocument::class);
    }

    public function visualAssets(): HasMany
    {
        return $this->hasMany(VisualAsset::class)->orderBy('sort_order');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ProjectComment::class);
    }
}
