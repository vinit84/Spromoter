<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getSetting(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            return $setting->value;
        }
        return $default;
    }

    public static function setSettings(array $settings): void
    {
        foreach ($settings as $key => $value) {
            self::updateOrCreate([
                'key' => $key,
            ], [
                'value' => $value,
            ]);
        }
    }

    public static function getSettings(array $keys): array
    {
        $settings = [];
        foreach ($keys as $key => $default) {
            $value = self::where('key', $key)
                ->first()
                ?->value;

            $settings[$key] = $value ?? ($default ?? null);
        }
        return $settings;
    }
}
