<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'phone',
        'source',
        'status',
        'interest',
        'message',
        'metadata',
        'converted_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'converted_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
