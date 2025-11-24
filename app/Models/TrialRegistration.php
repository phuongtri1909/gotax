<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'tool_type',
        'is_read',
        'verification_token',
        'verified_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'verified_at' => 'datetime',
    ];

    const TOOL_INVOICE = 'go-invoice';
    const TOOL_BOT = 'go-bot';
    const TOOL_SOFT = 'go-soft';
    const TOOL_QUICK = 'go-quick';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByToolType($query, $toolType)
    {
        return $query->where('tool_type', $toolType);
    }
}
