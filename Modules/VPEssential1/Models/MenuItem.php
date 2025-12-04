<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    protected $table = 'vp_menu_items';
    
    protected $fillable = [
        'menu_id',
        'title',
        'url',
        'target',
        'parent_id',
        'order',
        'icon',
        'attributes',
    ];
    
    protected $casts = [
        'attributes' => 'array',
    ];
    
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
