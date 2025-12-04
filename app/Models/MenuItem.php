<?php
/**
 * TCC School CMS - MenuItem Model
 * 
 * Eloquent model for managing individual menu items with hierarchical structure.
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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'page_id',
        'title',
        'url',
        'target',
        'icon',
        'css_class',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the menu
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the linked page
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the parent item
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Get child items
     */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get all descendants
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get the effective URL (from page if linked, otherwise use url field)
     */
    public function getEffectiveUrlAttribute(): string
    {
        if ($this->page_id && $this->page) {
            return '/' . ltrim($this->page->slug, '/');
        }
        return $this->url;
    }

    /**
     * Get the effective title (from page if not customized)
     */
    public function getEffectiveTitleAttribute(): string
    {
        if ($this->page_id && $this->page && empty($this->title)) {
            return $this->page->title;
        }
        return $this->title;
    }
}
