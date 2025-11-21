<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoBotPackage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'mst_limit',
        'discount_percent',
        'order',
        'status',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mst_limit' => 'integer',
        'discount_percent' => 'integer',
        'order' => 'integer',
        'status' => 'string',
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function purchases()
    {
        return $this->hasMany(GoBotPurchase::class, 'package_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}
