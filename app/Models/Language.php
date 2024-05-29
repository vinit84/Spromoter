<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $fillable = [
        'name',
        'code',
        'is_rtl',
        'is_system',
        'is_active',
    ];

    protected $casts = [
        'is_rtl' => 'boolean',
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include system languages.
     */
    public function scopeSystem($query): mixed
    {
        return $query->where('is_system', 1);
    }

    /**
     * Scope a query to only include active languages.
     */
    public function scopeActive($query): mixed
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope a query to only include RTL languages.
     */
    public function scopeRtl($query): mixed
    {
        return $query->where('is_rtl', 1);
    }

    /**
     * Scope a query to only include LTR languages.
     */
    public function scopeLtr($query): mixed
    {
        return $query->where('is_rtl', 0);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::created(function () {
            cacheForget('languages');
        });

        static::updated(function () {
            cacheForget('languages');
        });

        static::deleted(function () {
            cacheForget('languages');
        });
    }
}
