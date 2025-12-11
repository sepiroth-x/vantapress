<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;

class SocialSetting extends Model
{
    protected $table = 'vp_social_settings';
    
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
    ];
    
    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return static::castValue($setting->value, $setting->type);
    }
    
    /**
     * Set a setting value by key
     */
    public static function set(string $key, $value): void
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => static::prepareValue($value, $setting->type)]);
        } else {
            static::create([
                'key' => $key,
                'value' => static::prepareValue($value, 'string'),
                'type' => gettype($value),
            ]);
        }
    }
    
    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }
    
    /**
     * Prepare value for storage
     */
    protected static function prepareValue($value, string $type): string
    {
        if ($type === 'array' || $type === 'json') {
            return json_encode($value);
        }
        
        return (string) $value;
    }
    
    /**
     * Check if a feature is enabled
     */
    public static function isFeatureEnabled(string $feature): bool
    {
        return static::get("enable_{$feature}", true);
    }
}
