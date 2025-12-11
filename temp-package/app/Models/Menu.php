<?php
/**
 * TCC School CMS - Menu Model
 * 
 * Eloquent model for managing navigation menus.
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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu items
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }

    /**
     * Get active menu items
     */
    public function activeItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
            ->where('is_active', true)
            ->orderBy('order');
    }
}
