<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WidgetArea extends Model
{
    protected $table = 'vp_widget_areas';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function widgets(): HasMany
    {
        return $this->hasMany(Widget::class)->orderBy('order');
    }
}
