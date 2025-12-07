<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    protected static function booted()
    {
        static::created(function ($setting) {
            cache()->forget('setting:' . $setting->key);
        });

        static::updated(function ($setting) {
            cache()->forget('setting:' . $setting->key);
        });

        static::deleted(function ($setting) {
            cache()->forget('setting:' . $setting->key);
        });
    }

    public function getValueAttribute($value)
    {
        $type = $this->attributes['type'] ?? 'string';

        return match ($type) {
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }

    public function setValueAttribute($value)
    {
        $type = $this->attributes['type'] ?? 'string';

        $this->attributes['value'] = match ($type) {
            'array', 'json' => is_string($value) ? $value : json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };
    }

    /**
     * Get a setting value by key (with caching)
     */
    public static function getValue(string $key, $default = null)
    {
        return cache()->remember('setting:' . $key, 86400, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value by key
     */
    public static function setValue(string $key, $value, string $type = 'string', string $description = ''): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'description' => $description]
        );
    }
}
