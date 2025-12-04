<?php

namespace Modules\VPEssential1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'vp_menus';
    
    protected $fillable = [
        'name',
        'slug',
        'location',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('order');
    }
    
    public function getItemsHierarchical()
    {
        $items = $this->items()->get();
        return $this->buildTree($items);
    }
    
    private function buildTree($items, $parentId = null)
    {
        $branch = [];
        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildTree($items, $item->id);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }
        return $branch;
    }
}
