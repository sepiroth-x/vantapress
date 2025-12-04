<?php

namespace Modules\VPToDoList\Filament\Resources;

use Modules\VPToDoList\Filament\Resources\ProjectResource\Pages;
use Modules\VPToDoList\Models\Project;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';
    
    protected static ?string $navigationGroup = 'To Do List';
    
    protected static ?int $navigationGroupSort = 15;
    
    protected static ?string $navigationLabel = 'Projects';
    
    protected static ?int $navigationSort = 1;
    
    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Module::where('slug', 'VPToDoList')
            ->where('is_enabled', true)
            ->exists();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Project Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->placeholder('e.g., Website Redesign'),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Describe what this project is about...'),
                        
                        Forms\Components\ColorPicker::make('color')
                            ->label('Project Color')
                            ->default('#dc2626')
                            ->helperText('Choose a color to identify this project'),
                    ])
                    ->columns(1),
                
                Forms\Components\Section::make('Timeline & Status')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->native(false),
                        
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Due Date')
                            ->native(false)
                            ->after('start_date'),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'on_hold' => 'On Hold',
                                'completed' => 'Completed',
                                'archived' => 'Archived',
                            ])
                            ->default('active')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(3),
                
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->weight('bold')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->description ? \Illuminate\Support\Str::limit($record->description, 50) : null),
                
                Tables\Columns\ViewColumn::make('progress')
                    ->view('filament.tables.columns.project-progress')
                    ->label('Progress'),
                
                Tables\Columns\TextColumn::make('tasks_count')
                    ->counts('tasks')
                    ->label('Tasks')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'warning' => 'on_hold',
                        'info' => 'completed',
                        'gray' => 'archived',
                    ])
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->date('M d')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'on_hold' => 'On Hold',
                        'completed' => 'Completed',
                        'archived' => 'Archived',
                    ])
                    ->default('active'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewTasks')
                    ->label('View Tasks')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(fn ($record) => TaskResource::getUrl('index', ['tableFilters' => ['project_id' => ['value' => $record->id]]])),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->emptyStateHeading('No projects yet')
            ->emptyStateDescription('Create your first project to start organizing your tasks')
            ->emptyStateIcon('heroicon-o-folder-plus')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Project')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::where('user_id', auth()->id())
                ->where('status', 'active')
                ->count() ?: null;
        } catch (\Exception $e) {
            // Return null if table doesn't exist yet (module not fully installed)
            return null;
        }
    }
}
