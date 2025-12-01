<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoSoftPackage extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'mst_limit',
        'discount_percent',
        'badge',
        'order',
        'status',
        'description',
        'features',
    ];

    protected $casts = [
        'price' => 'integer',
        'mst_limit' => 'integer',
        'discount_percent' => 'integer',
        'order' => 'integer',
        'status' => 'string',
        'features' => 'array',
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
        return $this->hasMany(GoSoftPurchase::class, 'package_id');
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
