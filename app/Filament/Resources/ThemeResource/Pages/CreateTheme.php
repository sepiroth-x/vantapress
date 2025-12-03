<?php

namespace App\Filament\Resources\ThemeResource\Pages;

use App\Filament\Resources\ThemeResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;
use ZipArchive;

class CreateTheme extends CreateRecord
{
    protected static string $resource = ThemeResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle theme package upload and extraction
        if (isset($data['package_path']) && !empty($data['package_path'])) {
            try {
                $zipPath = storage_path('app/' . $data['package_path']);
                $extractPath = base_path('themes/' . $data['slug']);
                
                // Create themes directory if it doesn't exist
                if (!File::exists(base_path('themes'))) {
                    File::makeDirectory(base_path('themes'), 0755, true);
                }
                
                // Extract the ZIP file
                $zip = new ZipArchive();
                if ($zip->open($zipPath) === true) {
                    // Create theme directory
                    if (!File::exists($extractPath)) {
                        File::makeDirectory($extractPath, 0755, true);
                    }
                    
                    // Extract files
                    $zip->extractTo($extractPath);
                    $zip->close();
                    
                    // Clean up the uploaded ZIP file
                    File::delete($zipPath);
                    
                    // Look for theme.json to read metadata
                    $themeJsonPath = $extractPath . '/theme.json';
                    if (File::exists($themeJsonPath)) {
                        $themeData = json_decode(File::get($themeJsonPath), true);
                        
                        // Update data with theme.json information
                        $data['name'] = $themeData['name'] ?? $data['name'];
                        $data['description'] = $themeData['description'] ?? $data['description'];
                        $data['version'] = $themeData['version'] ?? $data['version'];
                        $data['author'] = $themeData['author'] ?? $data['author'];
                        
                        if (isset($themeData['config'])) {
                            $data['config'] = $themeData['config'];
                        }
                    }
                    
                    Notification::make()
                        ->title('Theme installed successfully')
                        ->success()
                        ->send();
                } else {
                    throw new \Exception('Failed to extract theme package');
                }
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Theme installation failed')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                throw $e;
            }
        }
        
        // Ensure only one active theme
        if (isset($data['is_active']) && $data['is_active']) {
            \App\Models\Theme::query()->update(['is_active' => false]);
        }
        
        return $data;
    }
}
