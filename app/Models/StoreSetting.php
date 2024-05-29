<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'key',
        'value',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function get(string $key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return $default;
    }

    public static function getSetting(Store $store, string $key, $default = null)
    {
        $setting = self::where('key', $key)
            ->where('store_id', $store->id)
            ->first();

        if ($setting) {
            return $setting->value;
        }
        return $default;
    }

    public static function setSettings(Store $store, array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::updateOrCreate([
                'store_id' => $store->id,
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }
    }

    public static function getSettings(Store $store, array $keys): array
    {
        $settings = [];
        foreach ($keys as $key) {
            $value = self::whereStoreId($store->id)
                ->where('key', $key)
                ->first()
                ?->value;

            $settings[$key] = $value ?? null;
        }
        return $settings;
    }
}
