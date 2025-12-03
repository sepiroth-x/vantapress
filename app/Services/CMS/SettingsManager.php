<?php
/**
 * TCC School CMS - Settings Manager
 * 
 * Manages system-wide settings for TCC School CMS.
 * Handles setting storage, retrieval, caching, and organization by groups.
 * 
 * @package TCC_School_CMS
 * @subpackage Services\CMS
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
 * 
 * This software is proprietary and confidential. Unauthorized copying,
 * modification, distribution, or use of this software, via any medium,
 * is strictly prohibited without explicit written permission from the author.
 */

namespace App\Services\CMS;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsManager
{
    protected string $cacheKey;
    protected int $cacheLifetime;
    protected bool $cacheEnabled;
    protected array $settings = [];

    public function __construct()
    {
        $this->cacheKey = config('cms.settings.cache_key', 'cms_settings');
        $this->cacheLifetime = config('cms.settings.cache_lifetime', 3600);
        $this->cacheEnabled = config('cms.settings.cache_enabled', true);
        $this->loadSettings();
    }

    /**
     * Load all settings from database
     *
     * @return void
     */
    protected function loadSettings(): void
    {
        if ($this->cacheEnabled && Cache::has($this->cacheKey)) {
            $this->settings = Cache::get($this->cacheKey);
            return;
        }

        $settings = Setting::where('autoload', true)->get();

        foreach ($settings as $setting) {
            $key = $setting->group ? "{$setting->group}.{$setting->key}" : $setting->key;
            $this->settings[$key] = $this->unserializeValue($setting->value, $setting->type);
        }

        if ($this->cacheEnabled) {
            Cache::put($this->cacheKey, $this->settings, $this->cacheLifetime);
        }
    }

    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        // Check if key contains group prefix
        if (str_contains($key, '.')) {
            return $this->settings[$key] ?? $default;
        }

        // Search for key in all groups
        foreach ($this->settings as $settingKey => $value) {
            if (str_ends_with($settingKey, ".{$key}")) {
                return $value;
            }
        }

        // Try direct key
        if (isset($this->settings[$key])) {
            return $this->settings[$key];
        }

        // Try loading from database if not in cache
        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            $value = $this->unserializeValue($setting->value, $setting->type);
            $fullKey = $setting->group ? "{$setting->group}.{$key}" : $key;
            $this->settings[$fullKey] = $value;
            return $value;
        }

        return $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string|null $group
     * @param string $type
     * @param bool $autoload
     * @return bool
     */
    public function set(
        string $key,
        mixed $value,
        ?string $group = null,
        string $type = 'string',
        bool $autoload = true
    ): bool {
        $serializedValue = $this->serializeValue($value, $type);

        $setting = Setting::updateOrCreate(
            ['key' => $key, 'group' => $group],
            [
                'value' => $serializedValue,
                'type' => $type,
                'autoload' => $autoload,
            ]
        );

        // Update local cache
        $fullKey = $group ? "{$group}.{$key}" : $key;
        $this->settings[$fullKey] = $value;

        // Clear cache
        $this->clearCache();

        do_action('setting_updated', $key, $value);

        return true;
    }

    /**
     * Delete a setting
     *
     * @param string $key
     * @param string|null $group
     * @return bool
     */
    public function delete(string $key, ?string $group = null): bool
    {
        $deleted = Setting::where('key', $key)
            ->when($group, function ($query, $group) {
                return $query->where('group', $group);
            })
            ->delete();

        if ($deleted) {
            $fullKey = $group ? "{$group}.{$key}" : $key;
            unset($this->settings[$fullKey]);
            $this->clearCache();

            do_action('setting_deleted', $key);
        }

        return $deleted > 0;
    }

    /**
     * Get all settings
     *
     * @param string|null $group
     * @return array
     */
    public function all(?string $group = null): array
    {
        if ($group === null) {
            return $this->settings;
        }

        $groupSettings = [];
        $prefix = "{$group}.";

        foreach ($this->settings as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $shortKey = substr($key, strlen($prefix));
                $groupSettings[$shortKey] = $value;
            }
        }

        return $groupSettings;
    }

    /**
     * Check if setting exists
     *
     * @param string $key
     * @param string|null $group
     * @return bool
     */
    public function has(string $key, ?string $group = null): bool
    {
        $fullKey = $group ? "{$group}.{$key}" : $key;

        if (isset($this->settings[$fullKey])) {
            return true;
        }

        return Setting::where('key', $key)
            ->when($group, function ($query, $group) {
                return $query->where('group', $group);
            })
            ->exists();
    }

    /**
     * Get all available groups
     *
     * @return array
     */
    public function getGroups(): array
    {
        return config('cms.settings.groups', []);
    }

    /**
     * Get settings by group
     *
     * @param string $group
     * @return array
     */
    public function getByGroup(string $group): array
    {
        return $this->all($group);
    }

    /**
     * Set multiple settings at once
     *
     * @param array $settings
     * @param string|null $group
     * @return bool
     */
    public function setMany(array $settings, ?string $group = null): bool
    {
        foreach ($settings as $key => $value) {
            $type = $this->detectType($value);
            $this->set($key, $value, $group, $type);
        }

        return true;
    }

    /**
     * Delete multiple settings
     *
     * @param array $keys
     * @param string|null $group
     * @return int
     */
    public function deleteMany(array $keys, ?string $group = null): int
    {
        $deleted = 0;

        foreach ($keys as $key) {
            if ($this->delete($key, $group)) {
                $deleted++;
            }
        }

        return $deleted;
    }

    /**
     * Flush all settings
     *
     * @param string|null $group
     * @return bool
     */
    public function flush(?string $group = null): bool
    {
        if ($group === null) {
            Setting::truncate();
            $this->settings = [];
        } else {
            Setting::where('group', $group)->delete();
            
            // Remove from local cache
            foreach (array_keys($this->settings) as $key) {
                if (str_starts_with($key, "{$group}.")) {
                    unset($this->settings[$key]);
                }
            }
        }

        $this->clearCache();

        do_action('settings_flushed', $group);

        return true;
    }

    /**
     * Export settings
     *
     * @param string|null $group
     * @return array
     */
    public function export(?string $group = null): array
    {
        $query = Setting::query();

        if ($group !== null) {
            $query->where('group', $group);
        }

        return $query->get()->map(function ($setting) {
            return [
                'group' => $setting->group,
                'key' => $setting->key,
                'value' => $this->unserializeValue($setting->value, $setting->type),
                'type' => $setting->type,
            ];
        })->toArray();
    }

    /**
     * Import settings
     *
     * @param array $settings
     * @param bool $overwrite
     * @return int
     */
    public function import(array $settings, bool $overwrite = false): int
    {
        $imported = 0;

        foreach ($settings as $setting) {
            if (!isset($setting['key']) || !isset($setting['value'])) {
                continue;
            }

            $exists = $this->has($setting['key'], $setting['group'] ?? null);

            if ($exists && !$overwrite) {
                continue;
            }

            $this->set(
                $setting['key'],
                $setting['value'],
                $setting['group'] ?? null,
                $setting['type'] ?? 'string'
            );

            $imported++;
        }

        return $imported;
    }

    /**
     * Serialize value based on type
     *
     * @param mixed $value
     * @param string $type
     * @return string
     */
    protected function serializeValue(mixed $value, string $type): string
    {
        return match ($type) {
            'json', 'array' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            'integer' => (string) intval($value),
            'float' => (string) floatval($value),
            default => (string) $value,
        };
    }

    /**
     * Unserialize value based on type
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    protected function unserializeValue(string $value, string $type): mixed
    {
        return match ($type) {
            'json', 'array' => json_decode($value, true),
            'boolean' => $value === '1' || $value === 'true',
            'integer' => intval($value),
            'float' => floatval($value),
            default => $value,
        };
    }

    /**
     * Detect value type
     *
     * @param mixed $value
     * @return string
     */
    protected function detectType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'array',
            default => 'string',
        };
    }

    /**
     * Clear settings cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        if ($this->cacheEnabled) {
            Cache::forget($this->cacheKey);
        }
    }

    /**
     * Refresh settings from database
     *
     * @return void
     */
    public function refresh(): void
    {
        $this->clearCache();
        $this->settings = [];
        $this->loadSettings();
    }

    /**
     * Get setting with type casting
     *
     * @param string $key
     * @param string $cast
     * @param mixed $default
     * @return mixed
     */
    public function getCast(string $key, string $cast, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);

        if ($value === null) {
            return $default;
        }

        return match ($cast) {
            'int', 'integer' => intval($value),
            'float', 'double' => floatval($value),
            'bool', 'boolean' => boolval($value),
            'string' => strval($value),
            'array' => is_array($value) ? $value : json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Toggle boolean setting
     *
     * @param string $key
     * @param string|null $group
     * @return bool
     */
    public function toggle(string $key, ?string $group = null): bool
    {
        $current = $this->getCast($key, 'bool', false);
        return $this->set($key, !$current, $group, 'boolean');
    }

    /**
     * Increment numeric setting
     *
     * @param string $key
     * @param int $amount
     * @param string|null $group
     * @return int
     */
    public function increment(string $key, int $amount = 1, ?string $group = null): int
    {
        $current = $this->getCast($key, 'int', 0);
        $new = $current + $amount;
        $this->set($key, $new, $group, 'integer');
        return $new;
    }

    /**
     * Decrement numeric setting
     *
     * @param string $key
     * @param int $amount
     * @param string|null $group
     * @return int
     */
    public function decrement(string $key, int $amount = 1, ?string $group = null): int
    {
        return $this->increment($key, -$amount, $group);
    }
}
