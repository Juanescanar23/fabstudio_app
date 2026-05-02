<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectPhase extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'status',
        'sort_order',
        'starts_at',
        'due_at',
        'completed_at',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'due_at' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class)->orderBy('sort_order');
    }
}
