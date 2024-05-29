<?php

namespace App\Models;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use function Illuminate\Events\queueable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;

class User extends Authenticatable implements HasMedia, MustVerifyEmail, IShopModel
{
    use
        HasApiTokens,
        HasFactory,
        HasRoles,
        Notifiable,
        InteractsWithMedia,
        SoftDeletes,
        Billable,
        ShopModel;

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'language_id' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Store log for all these events.
     *
     * @var array<int, string>
     */
    protected static array $recordEvents = ['created', 'updated', 'deleted'];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function subscription($storeId = null, $name = null)
    {
        $storeId = $storeId ?? activeStore()->id;
        return $this->subscriptions()
            ->when($name, fn (Builder $query) => $query->whereName($name))
            ->whereStoreId($storeId)
            ->first();
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->group === 'customer';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->group === 'admin';
    }


    public function getAvatarAttribute()
    {
        $name = str($this->name)
            ->replace('(', '')
            ->replace(')', '')
            ->title();

        return $this->profile_photo_url ? url($this->profile_photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }


    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(queueable(function (User $customer) {
            if ($customer->hasStripeId()) {
                $customer->syncStripeCustomerDetails();
            }
        }));

        static::creating(function (User $customer) {
            $customer->name = $customer->first_name . ' ' . $customer->last_name;
        });

        static::created(function (User $customer) {
            activity()
                ->performedOn($customer)
                ->causedBy($customer)
                ->log('Account created');
        });

        static::updating(function (User $customer) {
            $customer->name = $customer->first_name . ' ' . $customer->last_name;
        });
    }
}
