<?php
/**
 * TCC School CMS - Setting Model
 * 
 * Eloquent model for managing system-wide settings with grouping and caching.
 * 
 * @package TCC_School_CMS
 * @subpackage Models
 * @author Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * @version 1.0.0
 * @license Commercial / Paid
 * 
 * Copyright (c) 2025 Sepiroth X Villainous (Richard Cebel Cupal, LPT)
 * All Rights Reserved.
 * 
 * Contact Information:
 * Email: chardy.tsadiq02@gmail.com
 * Mobile: +63 915 0388 448
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'autoload',
    ];

    protected $casts = [
        'autoload' => 'boolean',
    ];

    /**
     * Get the full key with group prefix
     */
    public function getFullKeyAttribute(): string
    {
        return $this->group ? "{$this->group}.{$this->key}" : $this->key;
    }

    /**
     * Get the unserialized value
     */
    public function getValueAttribute($value)
    {
        return match ($this->type) {
            'json', 'array' => json_decode($value, true),
            'boolean' => $value === '1' || $value === 'true',
            'integer' => intval($value),
            'float' => floatval($value),
            default => $value,
        };
    }

    /**
     * Set the serialized value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match ($this->type) {
            'json', 'array' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            'integer' => (string) intval($value),
            'float' => (string) floatval($value),
            default => (string) $value,
        };
    }

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $parts = explode('.', $key);
        $group = count($parts) > 1 ? $parts[0] : null;
        $settingKey = count($parts) > 1 ? $parts[1] : $parts[0];

        $setting = static::query()
            ->where('key', $settingKey)
            ->when($group, fn($q) => $q->where('group', $group))
            ->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value, string $type = 'string'): void
    {
        $parts = explode('.', $key);
        $group = count($parts) > 1 ? $parts[0] : null;
        $settingKey = count($parts) > 1 ? $parts[1] : $parts[0];

        static::updateOrCreate(
            [
                'key' => $settingKey,
                'group' => $group,
            ],
            [
                'value' => $value,
                'type' => $type,
            ]
        );
    }
}
