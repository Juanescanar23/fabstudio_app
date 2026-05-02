<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'created_by_id',
        'version_number',
        'status',
        'content',
        'ai_model',
        'ai_prompt_hash',
        'pdf_path',
        'subtotal',
        'tax',
        'discount',
        'total',
        'reviewed_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
