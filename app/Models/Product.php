<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUuid;

    protected $guarded = ['id'];

    protected $casts = [
        'specs' => 'array'
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeUniqueId(Builder $query, $productId): Builder
    {
        return $query->where('unique_id', $productId);
    }
}
