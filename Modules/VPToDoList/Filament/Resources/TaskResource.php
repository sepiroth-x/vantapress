<?php

namespace Modules\VPToDoList\Filament\Resources;

use Modules\VPToDoList\Filament\Resources\TaskResource\Pages;
use Modules\VPToDoList\Models\Task;
use Modules\VPToDoList\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    
    protected static ?string $navigationGroup = 'To Do List';
    
    protected static ?int $navigationGroupSort = 15;
    
    protected static ?string $navigationLabel = 'My Tasks';
    
    protected static ?int $navigationSort = 2;
    
    public static function shouldRegisterNavigation(): bool
    {
        try {
            return \App\Models\Module::where('slug', 'VPToDoList')
                ->where('is_enabled', true)
                ->exists();
        } catch (\Exception $e) {
            // Return false if modules table doesn't exist yet
            return false;
        }
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Task Information')
                    ->schema([
                        Forms\Components\Select::make('project_id')
                            ->label('Project')
                            ->options(function () {
                                try {
                                    return Project::where('user_id', auth()->id())
                                        ->where('status', '!=', 'archived')
                                        ->pluck('name', 'id');
                                } catch (\Exception $e) {
                                    // Return empty array if table doesn't exist yet
                                    return [];
                                }
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2),
                                Forms\Components\ColorPicker::make('color')
                                    ->default('#dc2626'),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Project::create([
                                    ...$data,
                                    'user_id' => auth()->id(),
                                ])->id;
                            }),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Design homepage mockup')
                            ->columnSpanFull(),
                        
                        Forms\Components\RichEditor::make('description')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'link',
                            ])
                            ->placeholder('Add more details about this task...')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Task Details')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'todo' => 'To Do',
                                'in_progress' => 'In Progress',
                                'review' => 'Review',
                                'completed' => 'Completed',
                                'blocked' => 'Blocked',
                            ])
                            ->default('todo')
                            ->required()
                            ->native(false),
                        
                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->default('medium')
                            ->required()
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->native(false)
                            ->minDate(today()),
                        
                        Forms\Components\Toggle::make('is_pinned')
                            ->label('Pin this task')
                            ->helperText('Pinned tasks appear at the top'),
                        
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add tags...')
                            ->helperText('Press Enter to add a tag')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\IconColumn::make('is_pinned')
                    ->boolean()
                    ->label('')
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->action(function ($record) {
                        $record->update(['is_pinned' => !$record->is_pinned]);
                    })
                    ->tooltip(fn ($record) => $record->is_pinned ? 'Unpin' : 'Pin')
                    ->width('30px'),
                
                Tables\Columns\TextColumn::make('title')
                    ->weight('medium')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->project->name)
                    ->limit(40)
                    ->color(fn ($record) => $record->status === 'completed' ? 'gray' : null),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'todo',
                        'info' => 'in_progress',
                        'warning' => 'review',
                        'success' => 'completed',
                        'danger' => 'blocked',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->date('M d')
                    ->sortable()
                    ->color(fn ($record) => $record->is_overdue ? 'danger' : null)
                    ->icon(fn ($record) => $record->is_overdue ? 'heroicon-o-exclamation-triangle' : null)
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'todo' => 'To Do',
                        'in_progress' => 'In Progress',
                        'review' => 'Review',
                        'completed' => 'Completed',
                        'blocked' => 'Blocked',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(fn () => Project::where('user_id', auth()->id())
                        ->pluck('name', 'id')),
                
                Tables\Filters\TernaryFilter::make('is_pinned')
                    ->label('Pinned'),
                
                Tables\Filters\Filter::make('overdue')
                    ->query(fn (Builder $query) => $query->overdue())
                    ->label('Overdue'),
                
                Tables\Filters\Filter::make('due_today')
                    ->query(fn (Builder $query) => $query->dueToday())
                    ->label('Due Today'),
            ])
            ->actions([
                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'completed')
                    ->action(fn ($record) => $record->markAsCompleted())
                    ->requiresConfirmation(),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsCompleted')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each->markAsCompleted())
                        ->requiresConfirmation(),
                    
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('is_pinned', 'desc')->orderBy('order'))
            ->reorderable('order')
            ->groups([
                Tables\Grouping\Group::make('project.name')
                    ->label('Project')
                    ->collapsible(),
                Tables\Grouping\Group::make('status')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('priority')
                    ->label('Priority')
                    ->collapsible(),
            ])
            ->emptyStateHeading('No tasks yet')
            ->emptyStateDescription('Create your first task to get started')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Task')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('user_id', auth()->id())
                ->whereNotIn('status', ['completed'])
                ->count() ?: null;
        } catch (\Exception $e) {
            // Return null if table doesn't exist yet (module not fully installed)
            return null;
        }
    }
}
