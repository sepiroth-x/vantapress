<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeSetting extends Model
{
    protected $table = 'vp_theme_settings';
    
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];
    
    protected $casts = [
        'value' => 'string',
    ];
    
    public function getValueAttribute($value)
    {
        return match($this->type) {
            'json' => json_decode($value, true),
            'boolean' => (bool) $value,
            'integer' => (int) $value,
            default => $value,
        };
    }
    
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = match($this->type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => $value,
        };
    }
}
