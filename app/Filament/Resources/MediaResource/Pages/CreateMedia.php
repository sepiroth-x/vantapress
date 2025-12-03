<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Extract file information
        if (isset($data['path'])) {
            $file = storage_path('app/public/' . $data['path']);
            
            if (file_exists($file)) {
                $data['file_name'] = basename($data['path']);
                $data['mime_type'] = mime_content_type($file);
                $data['size'] = filesize($file);
                
                // Get image dimensions if it's an image
                if (str_starts_with($data['mime_type'], 'image/')) {
                    $imageInfo = getimagesize($file);
                    if ($imageInfo) {
                        $data['width'] = $imageInfo[0];
                        $data['height'] = $imageInfo[1];
                    }
                }
            }
        }
        
        // Set the uploader
        $data['uploaded_by'] = auth()->id();
        
        // Auto-set title from filename if not provided
        if (empty($data['title']) && isset($data['file_name'])) {
            $data['title'] = pathinfo($data['file_name'], PATHINFO_FILENAME);
        }
        
        return $data;
    }
}
