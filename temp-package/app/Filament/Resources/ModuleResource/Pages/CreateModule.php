<?php

namespace App\Filament\Resources\ModuleResource\Pages;

use App\Filament\Resources\ModuleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle module package upload and extraction
        if (isset($data['package_path']) && !empty($data['package_path'])) {
            try {
                $zipPath = storage_path('app/' . $data['package_path']);
                $extractPath = base_path('Modules/' . $data['slug']);
                
                // Create Modules directory if it doesn't exist
                if (!File::exists(base_path('Modules'))) {
                    File::makeDirectory(base_path('Modules'), 0755, true);
                }
                
                // Extract the ZIP file
                $zip = new ZipArchive();
                if ($zip->open($zipPath) === true) {
                    // Create module directory
                    if (!File::exists($extractPath)) {
                        File::makeDirectory($extractPath, 0755, true);
                    }
                    
                    // Extract files
                    $zip->extractTo($extractPath);
                    $zip->close();
                    
                    // Clean up the uploaded ZIP file
                    File::delete($zipPath);
                    
                    // Look for module.json to read metadata
                    $moduleJsonPath = $extractPath . '/module.json';
                    if (File::exists($moduleJsonPath)) {
                        $moduleData = json_decode(File::get($moduleJsonPath), true);
                        
                        // Update data with module.json information
                        $data['name'] = $moduleData['name'] ?? $data['name'];
                        $data['description'] = $moduleData['description'] ?? $data['description'];
                        $data['version'] = $moduleData['version'] ?? $data['version'];
                        $data['author'] = $moduleData['author'] ?? $data['author'];
                        
                        if (isset($moduleData['config'])) {
                            $data['config'] = $moduleData['config'];
                        }
                    }
                    
                    // Run module migrations if they exist
                    $migrationsPath = $extractPath . '/Database/Migrations';
                    if (File::exists($migrationsPath)) {
                        try {
                            Artisan::call('module:migrate', ['module' => $data['slug']]);
                        } catch (\Exception $e) {
                            // Migrations might not be set up yet
                        }
                    }
                    
                    // Publish module assets if they exist
                    $assetsPath = $extractPath . '/Resources/assets';
                    if (File::exists($assetsPath)) {
                        try {
                            Artisan::call('module:publish', ['module' => $data['slug']]);
                        } catch (\Exception $e) {
                            // Assets publication might not be set up yet
                        }
                    }
                    
                    Notification::make()
                        ->title('Module installed successfully')
                        ->success()
                        ->send();
                } else {
                    throw new \Exception('Failed to extract module package');
                }
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Module installation failed')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
                
                throw $e;
            }
        }
        
        return $data;
    }
}
