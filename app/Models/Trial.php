<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trial extends Model
{
    protected $fillable = [
        'tool_type',
        'mst_limit',
        'cccd_limit',
        'expires_days',
        'description',
        'status',
    ];

    protected $casts = [
        'mst_limit' => 'integer',
        'cccd_limit' => 'integer',
        'expires_days' => 'integer',
        'status' => 'boolean',
    ];

    const TOOL_INVOICE = 'go-invoice';
    const TOOL_BOT = 'go-bot';
    const TOOL_SOFT = 'go-soft';
    const TOOL_QUICK = 'go-quick';

    public static function getByToolType($toolType)
    {
        return self::where('tool_type', $toolType)
            ->where('status', true)
            ->first();
    }
}
