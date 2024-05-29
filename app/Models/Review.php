<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class Review extends Model implements HasMedia
{
    use HasFactory, HasUuid, SoftDeletes, InteractsWithMedia;

    const STATUS_PENDING = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SPAM = 'spam';
    const STATUS_REJECTED = 'rejected';

    protected $guarded = ['id'];

    protected $casts = [
        'attachments' => 'array',
        'is_verified' => 'boolean',
        'is_approved' => 'boolean',
        'is_purchased' => 'boolean',
        'device' => 'array',
        'location' => 'array',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ReviewComment::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ReviewComment::class);
    }

    public function reply(): HasOne
    {
        return $this->hasOne(ReviewComment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
}
