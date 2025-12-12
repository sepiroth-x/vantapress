<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ThemeLoader;
use App\Models\Theme;
use Illuminate\Support\Facades\File;

class SyncThemes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'themes:sync {--force : Force update existing themes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync themes from filesystem to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Syncing themes from filesystem to database...');
        $this->newLine();
        
        $loader = app(ThemeLoader::class);
        $themes = $loader->discoverThemes();
        
        if (empty($themes)) {
            $this->warn('⚠️  No themes found in themes/ directory');
            return 0;
        }
        
        $this->info("Found " . count($themes) . " theme(s) in filesystem:");
        $this->newLine();
        
        $synced = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($themes as $slug => $themeData) {
            try {
                $existing = Theme::where('slug', $slug)->first();
                
                if ($existing && !$this->option('force')) {
                    $this->line("⏭️  Skipped: <comment>{$themeData['name']}</comment> (already in database, use --force to update)");
                    $skipped++;
                    continue;
                }
                
                Theme::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $themeData['name'],
                        'description' => $themeData['description'] ?? '',
                        'version' => $themeData['version'],
                        'author' => $themeData['author'] ?? 'Unknown',
                        'config' => json_encode($themeData['config'] ?? []),
                        // Don't change is_active status during sync
                    ]
                );
                
                $this->line("✅ Synced: <info>{$themeData['name']}</info> (v{$themeData['version']})");
                $synced++;
                
            } catch (\Exception $e) {
                $this->error("❌ Error syncing {$slug}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   • Synced: {$synced}");
        $this->line("   • Skipped: {$skipped}");
        $this->line("   • Errors: {$errors}");
        
        if ($synced > 0) {
            $this->newLine();
            $this->info("🎉 Themes synchronized successfully!");
        }
        
        return 0;
    }
}
