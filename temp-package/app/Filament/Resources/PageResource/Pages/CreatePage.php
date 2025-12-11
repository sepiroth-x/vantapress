<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\UniqueConstraintViolationException;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = auth()->id();
        
        return $data;
    }
    
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        try {
            // Check if slug already exists (including soft-deleted)
            $existingPage = Page::withTrashed()->where('slug', $data['slug'])->first();
            
            if ($existingPage) {
                if ($existingPage->trashed()) {
                    Notification::make()
                        ->warning()
                        ->title('Slug Conflict with Deleted Page')
                        ->body("A deleted page with slug '{$data['slug']}' exists. Please restore it or use a different slug.")
                        ->persistent()
                        ->send();
                    
                    $this->halt();
                } else {
                    Notification::make()
                        ->danger()
                        ->title('Duplicate Slug')
                        ->body("A page with slug '{$data['slug']}' already exists. Please use a different slug.")
                        ->persistent()
                        ->send();
                    
                    $this->halt();
                }
            }
            
            return static::getModel()::create($data);
        } catch (UniqueConstraintViolationException $e) {
            Notification::make()
                ->danger()
                ->title('Database Error')
                ->body('This page slug already exists. Please use a unique slug.')
                ->persistent()
                ->send();
            
            $this->halt();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error Creating Page')
                ->body('An unexpected error occurred: ' . $e->getMessage())
                ->persistent()
                ->send();
            
            $this->halt();
        }
    }
}
