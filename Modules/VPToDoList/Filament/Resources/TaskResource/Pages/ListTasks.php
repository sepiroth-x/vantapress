<?php

namespace Modules\VPToDoList\Filament\Resources\TaskResource\Pages;

use Modules\VPToDoList\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Tasks'),
            
            'todo' => Tab::make('To Do')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'todo'))
                ->badge(fn () => static::getModel()::where('user_id', auth()->id())
                    ->where('status', 'todo')->count()),
            
            'in_progress' => Tab::make('In Progress')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'in_progress'))
                ->badge(fn () => static::getModel()::where('user_id', auth()->id())
                    ->where('status', 'in_progress')->count()),
            
            'review' => Tab::make('Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'review'))
                ->badge(fn () => static::getModel()::where('user_id', auth()->id())
                    ->where('status', 'review')->count()),
            
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'completed'))
                ->badge(fn () => static::getModel()::where('user_id', auth()->id())
                    ->where('status', 'completed')->count()),
            
            'overdue' => Tab::make('Overdue')
                ->modifyQueryUsing(fn (Builder $query) => $query->overdue())
                ->badge(fn () => static::getModel()::where('user_id', auth()->id())
                    ->overdue()->count())
                ->badgeColor('danger'),
        ];
    }
}
