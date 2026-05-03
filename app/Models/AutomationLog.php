<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AutomationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'automation_key',
        'category',
        'severity',
        'status',
        'subject_type',
        'subject_id',
        'recipient_type',
        'recipient_id',
        'recipient_email',
        'channel',
        'title',
        'summary',
        'payload',
        'deduplication_key',
        'processed_at',
        'notified_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'processed_at' => 'datetime',
            'notified_at' => 'datetime',
        ];
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo();
    }
}
