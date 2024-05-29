<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class OrderEmail extends Model
{
    use SoftDeletes, HasUuid;

    protected $guarded = [];

    protected $casts = [
        'sent_at' => 'datetime',
        'opened_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->item->product();
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function scopeCurrentStore(Builder $query): Builder
    {
        $activeStore = activeStore();
        return $query->where('store_id', $activeStore->id);
    }
}
