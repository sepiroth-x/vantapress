<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Theme;
use App\Services\ModuleLoader;
use App\Services\ThemeLoader;

class ModuleThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedModules();
        $this->seedThemes();
    }

    /**
     * Seed modules from file system
     */
    protected function seedModules(): void
    {
        $moduleLoader = app(ModuleLoader::class);
        $modules = $moduleLoader->discoverModules();

        foreach ($modules as $slug => $metadata) {
            Module::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $metadata['name'] ?? $slug,
                    'description' => $metadata['description'] ?? '',
                    'version' => $metadata['version'] ?? '1.0.0',
                    'author' => $metadata['author'] ?? '',
                    'is_enabled' => $metadata['active'] ?? false,
                    'path' => $metadata['path'] ?? '',
                    'config' => $metadata,
                ]
            );
        }

        $this->command->info('Modules synced from file system');
    }

    /**
     * Seed themes from file system
     */
    protected function seedThemes(): void
    {
        $themeLoader = app(ThemeLoader::class);
        $themes = $themeLoader->discoverThemes();

        foreach ($themes as $slug => $metadata) {
            Theme::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $metadata['name'] ?? $slug,
                    'description' => $metadata['description'] ?? '',
                    'version' => $metadata['version'] ?? '1.0.0',
                    'author' => $metadata['author'] ?? '',
                    'is_active' => $metadata['active'] ?? false,
                    'path' => $metadata['path'] ?? '',
                    'config' => $metadata,
                ]
            );
        }
        
        // Ensure at least one theme is active (prefer Basic Theme)
        Theme::ensureActiveTheme();

        $this->command->info('Themes synced from file system');
    }
}
