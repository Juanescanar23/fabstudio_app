<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_document_id',
        'uploaded_by_id',
        'version_number',
        'original_name',
        'file_path',
        'disk',
        'mime_type',
        'size',
        'checksum',
        'notes',
        'is_current',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(ProjectDocument::class, 'project_document_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_id');
    }
}
