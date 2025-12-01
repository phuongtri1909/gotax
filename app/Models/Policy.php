<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Policy extends Model
{
    protected $fillable = [
        'title',
        'content',
        'slug',
        'order',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($policy) {
            if (empty($policy->slug)) {
                $policy->slug = Str::slug($policy->title);
            }
        });

        static::updating(function ($policy) {
            if ($policy->isDirty('title') && empty($policy->slug)) {
                $policy->slug = Str::slug($policy->title);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc')->orderBy('id', 'desc');
    }
}
