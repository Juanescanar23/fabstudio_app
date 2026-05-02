<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'created_by_id',
        'name',
        'type',
        'status',
        'currency',
        'default_valid_days',
        'description',
        'sections',
        'line_items',
        'terms',
        'ai_instructions',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'default_valid_days' => 'integer',
            'sections' => 'array',
            'line_items' => 'array',
            'metadata' => 'array',
        ];
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function quoteVersions(): HasMany
    {
        return $this->hasMany(QuoteVersion::class);
    }
}
