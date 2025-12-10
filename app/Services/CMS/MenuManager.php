<?php
/**
 * TCC School CMS - Menu Manager
 * 
 * Manages the menu system for TCC School CMS.
 * Handles menu creation, item management, rendering, and hierarchical structures.
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
use App\Models\Menu;
use App\Models\MenuItem;

class MenuManager
{
    protected string $cacheKey;
    protected int $cacheLifetime;
    protected bool $cacheEnabled;

    public function __construct()
    {
        $this->cacheKey = config('cms.menus.cache_key', 'cms_menus');
        $this->cacheLifetime = config('cms.menus.cache_lifetime', 3600);
        $this->cacheEnabled = config('cms.menus.cache_enabled', true);
    }

    /**
     * Create a new menu
     *
     * @param string $location
     * @param string $name
     * @return Menu
     */
    public function createMenu(string $location, string $name): Menu
    {
        $menu = Menu::create([
            'name' => $name,
            'location' => $location,
            'is_active' => true,
        ]);

        $this->clearCache();

        do_action('menu_created', $menu);

        return $menu;
    }

    /**
     * Add a menu item
     *
     * @param int $menuId
     * @param array $data
     * @return MenuItem
     */
    public function addMenuItem(int $menuId, array $data): MenuItem
    {
        $item = MenuItem::create([
            'menu_id' => $menuId,
            'parent_id' => $data['parent_id'] ?? null,
            'title' => $data['title'],
            'url' => $data['url'] ?? '#',
            'target' => $data['target'] ?? '_self',
            'icon' => $data['icon'] ?? null,
            'css_class' => $data['css_class'] ?? null,
            'order' => $data['order'] ?? $this->getNextOrder($menuId, $data['parent_id'] ?? null),
            'is_active' => $data['is_active'] ?? true,
        ]);

        $this->clearCache();

        do_action('menu_item_added', $item);

        return $item;
    }

    /**
     * Update menu item
     *
     * @param int $itemId
     * @param array $data
     * @return bool
     */
    public function updateMenuItem(int $itemId, array $data): bool
    {
        $item = MenuItem::find($itemId);

        if (!$item) {
            return false;
        }

        $item->update($data);

        $this->clearCache();

        do_action('menu_item_updated', $item);

        return true;
    }

    /**
     * Delete menu item
     *
     * @param int $itemId
     * @return bool
     */
    public function deleteMenuItem(int $itemId): bool
    {
        $item = MenuItem::find($itemId);

        if (!$item) {
            return false;
        }

        // Delete children
        MenuItem::where('parent_id', $itemId)->delete();

        $item->delete();

        $this->clearCache();

        do_action('menu_item_deleted', $itemId);

        return true;
    }

    /**
     * Get menu by location
     *
     * @param string $location
     * @return array
     */
    public function getMenu(string $location): array
    {
        $cacheKey = $this->cacheKey . '_' . $location;

        if ($this->cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $menu = Menu::where('location', $location)
            ->where('is_active', true)
            ->with(['items' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('order', 'asc');
            }])
            ->first();

        if (!$menu) {
            return [];
        }

        $items = $this->buildMenuTree($menu->items);

        $menuData = [
            'id' => $menu->id,
            'name' => $menu->name,
            'location' => $menu->location,
            'items' => $items,
        ];

        if ($this->cacheEnabled) {
            Cache::put($cacheKey, $menuData, $this->cacheLifetime);
        }

        return $menuData;
    }

    /**
     * Build hierarchical menu tree
     *
     * @param \Illuminate\Support\Collection $items
     * @param int|null $parentId
     * @return array
     */
    protected function buildMenuTree($items, ?int $parentId = null): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $children = $this->buildMenuTree($items, $item->id);
                
                $menuItem = [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => $item->url,
                    'target' => $item->target,
                    'icon' => $item->icon,
                    'css_class' => $item->css_class,
                    'order' => $item->order,
                    'children' => $children,
                ];

                $tree[] = $menuItem;
            }
        }

        return $tree;
    }

    /**
     * Render menu HTML
     *
     * @param string $location
     * @param array $options
     * @return string
     */
    public function renderMenu(string $location, array $options = []): string
    {
        $menu = $this->getMenu($location);

        if (empty($menu)) {
            return '';
        }

        $defaults = [
            'container' => 'nav',
            'container_class' => 'menu-' . $location,
            'menu_class' => 'menu',
            'item_class' => 'menu-item',
            'link_class' => 'menu-link',
            'active_class' => 'active',
            'depth' => config('cms.menus.max_depth', 5),
        ];

        $options = array_merge($defaults, $options);

        return $this->renderMenuItems($menu['items'], $options, 0);
    }

    /**
     * Render menu items HTML
     *
     * @param array $items
     * @param array $options
     * @param int $depth
     * @return string
     */
    protected function renderMenuItems(array $items, array $options, int $depth): string
    {
        if ($depth >= $options['depth']) {
            return '';
        }

        $html = '<ul class="' . $options['menu_class'] . ($depth > 0 ? ' submenu' : '') . '">';

        foreach ($items as $item) {
            $isActive = $this->isMenuItemActive($item['url']);
            $itemClass = $options['item_class'];
            
            if ($isActive) {
                $itemClass .= ' ' . $options['active_class'];
            }

            if (!empty($item['children'])) {
                $itemClass .= ' has-children';
            }

            if (!empty($item['css_class'])) {
                $itemClass .= ' ' . $item['css_class'];
            }

            $html .= '<li class="' . $itemClass . '">';
            
            $html .= '<a href="' . $item['url'] . '" ';
            $html .= 'class="' . $options['link_class'] . '" ';
            $html .= 'target="' . $item['target'] . '">';
            
            if (!empty($item['icon'])) {
                $html .= '<i class="' . $item['icon'] . '"></i> ';
            }
            
            $html .= $item['title'];
            $html .= '</a>';

            if (!empty($item['children'])) {
                $html .= $this->renderMenuItems($item['children'], $options, $depth + 1);
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Check if menu item is active
     *
     * @param string $url
     * @return bool
     */
    protected function isMenuItemActive(string $url): bool
    {
        if ($url === '#') {
            return false;
        }

        $currentUrl = request()->url();
        $itemUrl = url($url);

        return $currentUrl === $itemUrl || str_starts_with($currentUrl, $itemUrl);
    }

    /**
     * Get all menus
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return Menu::with('items')->get();
    }

    /**
     * Delete menu
     *
     * @param int $menuId
     * @return bool
     */
    public function deleteMenu(int $menuId): bool
    {
        $menu = Menu::find($menuId);

        if (!$menu) {
            return false;
        }

        // Delete all menu items
        MenuItem::where('menu_id', $menuId)->delete();

        $menu->delete();

        $this->clearCache();

        do_action('menu_deleted', $menuId);

        return true;
    }

    /**
     * Update menu order
     *
     * @param int $menuId
     * @param array $itemsOrder
     * @return bool
     */
    public function updateOrder(int $menuId, array $itemsOrder): bool
    {
        foreach ($itemsOrder as $order => $itemId) {
            MenuItem::where('id', $itemId)
                ->where('menu_id', $menuId)
                ->update(['order' => $order]);
        }

        $this->clearCache();

        do_action('menu_order_updated', $menuId);

        return true;
    }

    /**
     * Update menu item hierarchy
     *
     * @param int $itemId
     * @param int|null $parentId
     * @param int $order
     * @return bool
     */
    public function updateHierarchy(int $itemId, ?int $parentId, int $order): bool
    {
        $item = MenuItem::find($itemId);

        if (!$item) {
            return false;
        }

        // Check max depth
        if ($parentId !== null) {
            $depth = $this->getItemDepth($parentId);
            $maxDepth = config('cms.menus.max_depth', 5);

            if ($depth >= $maxDepth) {
                return false;
            }
        }

        $item->update([
            'parent_id' => $parentId,
            'order' => $order,
        ]);

        $this->clearCache();

        return true;
    }

    /**
     * Get item depth in hierarchy
     *
     * @param int $itemId
     * @return int
     */
    protected function getItemDepth(int $itemId): int
    {
        $depth = 1;
        $item = MenuItem::find($itemId);

        while ($item && $item->parent_id !== null) {
            $depth++;
            $item = MenuItem::find($item->parent_id);
        }

        return $depth;
    }

    /**
     * Get next order number for menu items
     *
     * @param int $menuId
     * @param int|null $parentId
     * @return int
     */
    protected function getNextOrder(int $menuId, ?int $parentId = null): int
    {
        $maxOrder = MenuItem::where('menu_id', $menuId)
            ->where('parent_id', $parentId)
            ->max('order');

        return ($maxOrder ?? 0) + 1;
    }

    /**
     * Get available menu locations
     *
     * @return array
     */
    public function getLocations(): array
    {
        return config('cms.menus.locations', []);
    }

    /**
     * Check if location has menu
     *
     * @param string $location
     * @return bool
     */
    public function hasMenu(string $location): bool
    {
        return Menu::where('location', $location)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Clear menu cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        if ($this->cacheEnabled) {
            Cache::forget($this->cacheKey);
            
            // Clear location-specific caches
            foreach ($this->getLocations() as $location => $name) {
                Cache::forget($this->cacheKey . '_' . $location);
            }
        }
    }

    /**
     * Duplicate menu
     *
     * @param int $menuId
     * @param string $newName
     * @param string $newLocation
     * @return Menu|null
     */
    public function duplicateMenu(int $menuId, string $newName, string $newLocation): ?Menu
    {
        $originalMenu = Menu::with('items')->find($menuId);

        if (!$originalMenu) {
            return null;
        }

        $newMenu = Menu::create([
            'name' => $newName,
            'location' => $newLocation,
            'is_active' => false,
        ]);

        foreach ($originalMenu->items as $item) {
            MenuItem::create([
                'menu_id' => $newMenu->id,
                'parent_id' => $item->parent_id,
                'title' => $item->title,
                'url' => $item->url,
                'target' => $item->target,
                'icon' => $item->icon,
                'css_class' => $item->css_class,
                'order' => $item->order,
                'is_active' => $item->is_active,
            ]);
        }

        return $newMenu;
    }
}
