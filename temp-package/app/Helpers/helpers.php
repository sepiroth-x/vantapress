<?php
/**
 * TCC School CMS - Helper Functions
 * 
 * WordPress-style helper functions for TCC School CMS.
 * Provides convenient functions for modules, themes, hooks, settings, and school operations.
 * 
 * @package TCC_School_CMS
 * @subpackage Helpers
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

if (!function_exists('cms_version')) {
    /**
     * Get CMS version
     *
     * @return string
     */
    function cms_version(): string
    {
        return config('cms.version', '1.0.0');
    }
}

if (!function_exists('module_enabled')) {
    /**
     * Check if a module is enabled
     *
     * @param string $module
     * @return bool
     */
    function module_enabled(string $module): bool
    {
        return app(\App\Services\CMS\ModuleManager::class)->isEnabled($module);
    }
}

if (!function_exists('active_theme')) {
    /**
     * Get the active theme name
     *
     * @return string
     */
    function active_theme(): string
    {
        return app(\App\Services\CMS\ThemeManager::class)->getActiveTheme();
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get theme asset URL
     *
     * @param string $path
     * @param string|null $theme
     * @return string
     */
    function theme_asset(string $path, ?string $theme = null): string
    {
        $theme = $theme ?? active_theme();
        return asset("themes/{$theme}/assets/{$path}");
    }
}

if (!function_exists('theme_view')) {
    /**
     * Get theme view path
     *
     * @param string $view
     * @param string|null $theme
     * @return string
     */
    function theme_view(string $view, ?string $theme = null): string
    {
        $theme = $theme ?? active_theme();
        return "themes.{$theme}.{$view}";
    }
}

if (!function_exists('do_action')) {
    /**
     * Execute a WordPress-style action hook
     *
     * @param string $hook
     * @param mixed ...$args
     * @return void
     */
    function do_action(string $hook, ...$args): void
    {
        app(\App\Services\CMS\HookManager::class)->doAction($hook, ...$args);
    }
}

if (!function_exists('add_action')) {
    /**
     * Add a WordPress-style action hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    function add_action(string $hook, callable $callback, int $priority = 10): void
    {
        app(\App\Services\CMS\HookManager::class)->addAction($hook, $callback, $priority);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * Apply WordPress-style filters
     *
     * @param string $filter
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     */
    function apply_filters(string $filter, mixed $value, ...$args): mixed
    {
        return app(\App\Services\CMS\HookManager::class)->applyFilters($filter, $value, ...$args);
    }
}

if (!function_exists('add_filter')) {
    /**
     * Add a WordPress-style filter
     *
     * @param string $filter
     * @param callable $callback
     * @param int $priority
     * @return void
     */
    function add_filter(string $filter, callable $callback, int $priority = 10): void
    {
        app(\App\Services\CMS\HookManager::class)->addFilter($filter, $callback, $priority);
    }
}

if (!function_exists('get_setting')) {
    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function get_setting(string $key, mixed $default = null): mixed
    {
        return app(\App\Services\CMS\SettingsManager::class)->get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    function set_setting(string $key, mixed $value): bool
    {
        return app(\App\Services\CMS\SettingsManager::class)->set($key, $value);
    }
}

if (!function_exists('get_menu')) {
    /**
     * Get a menu by location
     *
     * @param string $location
     * @return array
     */
    function get_menu(string $location): array
    {
        return app(\App\Services\CMS\MenuManager::class)->getMenu($location);
    }
}

if (!function_exists('system_year')) {
    /**
     * Get current system year
     *
     * @return string
     */
    function system_year(): string
    {
        return config('cms.project.year_start');
    }
}

if (!function_exists('grade_passing')) {
    /**
     * Check if grade is passing
     *
     * @param float $grade
     * @return bool
     */
    function grade_passing(float $grade): bool
    {
        return $grade <= config('cms.project.grading_scale.passing', 3.0);
    }
}

if (!function_exists('calculate_final_grade')) {
    /**
     * Calculate final grade from term grades
     *
     * @param float $prelim
     * @param float $midterm
     * @param float $semifinal
     * @param float $finals
     * @return float
     */
    function calculate_final_grade(float $prelim, float $midterm, float $semifinal, float $finals): float
    {
        $grades = array_filter([$prelim, $midterm, $semifinal, $finals], fn($g) => $g > 0);
        return empty($grades) ? 0 : round(array_sum($grades) / count($grades), 2);
    }
}

if (!function_exists('grade_remarks')) {
    /**
     * Get remarks for a grade
     *
     * @param float $grade
     * @return string
     */
    function grade_remarks(float $grade): string
    {
        if ($grade <= 0) {
            return 'No Grade';
        }
        return grade_passing($grade) ? 'Passed' : 'Failed';
    }
}

if (!function_exists('format_student_id')) {
    /**
     * Format student ID with project code
     *
     * @param string $id
     * @return string
     */
    function format_student_id(string $id): string
    {
        $code = config('cms.project.code', 'VP');
        return "{$code}-{$id}";
    }
}

if (!function_exists('academic_years')) {
    /**
     * Generate academic years array
     *
     * @param int $startYear
     * @param int $count
     * @return array
     */
    function academic_years(int $startYear = null, int $count = 5): array
    {
        $startYear = $startYear ?? (int)date('Y');
        $years = [];
        
        for ($i = 0; $i < $count; $i++) {
            $year = $startYear + $i;
            $nextYear = $year + 1;
            $years[] = "{$year}-{$nextYear}";
        }
        
        return $years;
    }
}

if (!function_exists('current_semester')) {
    /**
     * Get current semester based on month
     *
     * @return string
     */
    function current_semester(): string
    {
        $month = (int)date('n');
        
        if ($month >= 6 && $month <= 10) {
            return 'First';
        } elseif ($month >= 11 || $month <= 3) {
            return 'Second';
        } else {
            return 'Summer';
        }
    }
}

if (!function_exists('breadcrumbs')) {
    /**
     * Generate breadcrumbs array
     *
     * @return array
     */
    function breadcrumbs(): array
    {
        $breadcrumbs = [
            ['title' => 'Home', 'url' => route('home')],
        ];
        
        $segments = request()->segments();
        $url = '';
        
        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $breadcrumbs[] = [
                'title' => ucwords(str_replace('-', ' ', $segment)),
                'url' => $url,
            ];
        }
        
        return $breadcrumbs;
    }
}
