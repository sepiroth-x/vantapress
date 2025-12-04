<?php

return array (
  'name' => 'VantaPress',
  'version' => '1.0.0',
  'description' => 'Talisay City College School Management System',
  'modules' => 
  array (
    'path' => 'Modules',
    'cache_enabled' => true,
    'cache_key' => 'cms_modules',
    'cache_lifetime' => 3600,
    'auto_discover' => true,
    'auto_register_routes' => true,
    'auto_register_views' => true,
    'auto_register_migrations' => true,
  ),
  'themes' => 
  array (
    'path' => 'themes',
    'active' => 'default',
    'active_theme' => 'default',
    'cache_enabled' => true,
    'cache_key' => 'cms_themes',
    'cache_lifetime' => 3600,
    'fallback_theme' => 'default',
    'allowed_extensions' => 
    array (
      0 => 'blade.php',
      1 => 'php',
      2 => 'css',
      3 => 'js',
      4 => 'json',
    ),
    'assets_path' => 'assets',
  ),
  'hooks' => 
  array (
    'enabled' => true,
    'cache_enabled' => true,
    'priority_range' => 
    array (
      0 => 1,
      1 => 100,
    ),
    'default_priority' => 10,
  ),
  'menus' => 
  array (
    'cache_enabled' => true,
    'cache_lifetime' => 3600,
    'max_depth' => 5,
    'locations' => 
    array (
      'primary' => 'Primary Menu',
      'footer' => 'Footer Menu',
      'sidebar' => 'Sidebar Menu',
    ),
  ),
  'settings' => 
  array (
    'cache_enabled' => true,
    'cache_key' => 'cms_settings',
    'cache_lifetime' => 3600,
    'groups' => 
    array (
      'general' => 'General Settings',
      'project' => 'Project Settings',
      'appearance' => 'Appearance Settings',
      'enrollment' => 'Enrollment Settings',
      'grading' => 'Grading Settings',
    ),
  ),
  'assets' => 
  array (
    'cdn_enabled' => false,
    'cdn_url' => '',
    'minify_enabled' => false,
    'combine_enabled' => false,
  ),
  'project' => 
  array (
    'name' => env('PROJECT_NAME', 'VantaPress'),
    'code' => env('PROJECT_CODE', 'VP'),
    'year_start' => env('SYSTEM_YEAR_START', '2024'),
    'grading_scale' => 
    array (
      'min' => 1.0,
      'max' => 5.0,
      'passing' => 3.0,
    ),
    'year_levels' => 
    array (
      0 => 1,
      1 => 2,
      2 => 3,
      3 => 4,
    ),
    'semesters' => 
    array (
      0 => 'First',
      1 => 'Second',
      2 => 'Summer',
    ),
  ),
  'uploads' => 
  array (
    'max_size' => 10240,
    'allowed_types' => 
    array (
      'images' => 
      array (
        0 => 'jpg',
        1 => 'jpeg',
        2 => 'png',
        3 => 'gif',
        4 => 'webp',
      ),
      'documents' => 
      array (
        0 => 'pdf',
        1 => 'doc',
        2 => 'docx',
        3 => 'xls',
        4 => 'xlsx',
      ),
      'modules' => 
      array (
        0 => 'zip',
      ),
      'themes' => 
      array (
        0 => 'zip',
      ),
    ),
    'paths' => 
    array (
      'modules' => 'uploads/modules',
      'themes' => 'uploads/themes',
      'profiles' => 'uploads/profiles',
      'documents' => 'uploads/documents',
    ),
  ),
  'security' => 
  array (
    'enable_csrf' => true,
    'enable_xss_protection' => true,
    'enable_sql_injection_protection' => true,
    'allowed_html_tags' => 
    array (
      0 => 'p',
      1 => 'br',
      2 => 'strong',
      3 => 'em',
      4 => 'u',
      5 => 'a',
      6 => 'img',
      7 => 'ul',
      8 => 'ol',
      9 => 'li',
    ),
  ),
  'performance' => 
  array (
    'enable_query_cache' => true,
    'enable_view_cache' => true,
    'enable_route_cache' => true,
    'enable_config_cache' => true,
  ),
  'active_theme' => 'TheVillainArise',
);
