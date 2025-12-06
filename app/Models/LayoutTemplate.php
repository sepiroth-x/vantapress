<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'layout_data',
        'category',
        'is_global',
        'theme_id',
    ];

    protected $casts = [
        'layout_data' => 'array',
        'is_global' => 'boolean',
    ];

    /**
     * Get the theme this template belongs to
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }
}
