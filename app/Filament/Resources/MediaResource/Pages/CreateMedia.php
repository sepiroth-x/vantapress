<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            // Extract file information
            if (isset($data['path'])) {
                // Try multiple path variations
                $possiblePaths = [
                    storage_path('app/public/' . $data['path']),
                    public_path($data['path']),
                    storage_path('app/' . $data['path']),
                ];
                
                $file = null;
                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $file = $path;
                        break;
                    }
                }
                
                if ($file && file_exists($file)) {
                    $data['file_name'] = basename($data['path']);
                    $data['mime_type'] = mime_content_type($file);
                    
                    // Get file size
                    $data['size'] = filesize($file);
                    
                    // Get image dimensions if it's an image
                    if (str_starts_with($data['mime_type'], 'image/')) {
                        $imageInfo = @getimagesize($file);
                        if ($imageInfo) {
                            $data['width'] = $imageInfo[0];
                            $data['height'] = $imageInfo[1];
                        }
                    }
                    
                    // Auto-generate title from filename if not provided
                    if (empty($data['title'])) {
                        $data['title'] = ucwords(str_replace(['-', '_'], ' ', pathinfo($data['file_name'], PATHINFO_FILENAME)));
                    }
                }
            }
            
            // Set the uploader
            $data['uploaded_by'] = auth()->id();
            
            return $data;
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error Processing Media')
                ->body('Failed to process uploaded file: ' . $e->getMessage())
                ->persistent()
                ->send();
            
            throw $e;
        }
    }
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            return static::getModel()::create($data);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            Notification::make()
                ->danger()
                ->title('Database Error')
                ->body('A media file with this path already exists.')
                ->persistent()
                ->send();
            
            $this->halt();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error Creating Media')
                ->body('An unexpected error occurred: ' . $e->getMessage())
                ->persistent()
                ->send();
            
            $this->halt();
        }
    }
}
