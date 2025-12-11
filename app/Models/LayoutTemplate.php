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
        'theme_slug',
    ];

    protected $casts = [
        'layout_data' => 'array',
        'is_global' => 'boolean',
    ];

    /**
     * Get the theme slug for this template
     */
    public function getThemeSlug(): ?string
    {
        return $this->theme_slug;
    }
}
