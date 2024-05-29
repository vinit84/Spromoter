<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class Store extends Model implements HasMedia
{
    use HasFactory, HasUuid, InteractsWithMedia, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'user_id' => 'integer',
        'store_category_id' => 'integer',
        'preview_image_updated_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(StoreCategory::class, 'store_category_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function emails(): HasMany
    {
        return $this->hasMany(OrderEmail::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(StoreSetting::class);
    }

    public function setting($key = null, $default = null): mixed
    {
        return StoreSetting::whereStoreId($this->id)
            ->where('key', $key)
            ->first()?->value ?? $default;
    }

    public function profaneWords(): HasMany
    {
        return $this->hasMany(ProfaneWord::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeIntegrated(Builder $query): Builder
    {
        return $query->where('is_integrated', true);
    }

    public function scopeNotVerified(Builder $query): Builder
    {
        return $query->where('is_verified', false);
    }

    public function scopeNotApproved(Builder $query): Builder
    {
        return $query->where('is_approved', false);
    }

    public function scopeNotIntegrated(Builder $query): Builder
    {
        return $query->where('is_integrated', false);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (Store $store) {
            StoreSetting::setSettings($store, [
                'emails.review_request_email_days' => 2,
                'emails.review_request_email_subject' => "What do you think of your purchase from {store.name}?",
                'emails.review_request_email_body' => "<p>Hello {customer.name} ! Thank you so much for shopping at {store.name} . We'd love to hear what you think about your new {product.name}</p>"
            ]);


            // Initial Subscription
            $user = $store->user;
            $user->createOrGetStripeCustomer([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => [
                    'line1' => $user->address,
                    'city' => $user->city,
                    'state' => $user->state,
                    'postal_code' => $user->postal_code,
                    'country' => $user->country,
                ],
            ]);


            $paymentMethod = $user->defaultPaymentMethod();

            $user->newSubscription('Free', config('app.stripe_free_plan_price_id'))
                ->withMetadata([
                    'store_id' => $store->id,
                    'interval' => 'monthly',
                    'total_orders' => 50
                ])
                ->create($paymentMethod);
        });
    }
}
