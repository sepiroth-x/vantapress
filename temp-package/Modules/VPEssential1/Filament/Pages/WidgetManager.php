<?php

namespace Modules\VPEssential1\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Modules\VPEssential1\Models\WidgetArea;
use Modules\VPEssential1\Models\Widget;
use Filament\Notifications\Notification;

class WidgetManager extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Widget Manager';
    protected static ?string $navigationGroup = 'VP Essential';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'VPEssential1::filament.pages.widget-manager';

    public ?int $selectedAreaId = null;

    public function mount(): void
    {
        // Create default widget areas if they don't exist
        $this->ensureDefaultWidgetAreas();

        $firstArea = WidgetArea::first();
        if ($firstArea) {
            $this->selectedAreaId = $firstArea->id;
        }
    }

    protected function ensureDefaultWidgetAreas(): void
    {
        $defaultAreas = [
            ['name' => 'Header Widget Area', 'slug' => 'header', 'description' => 'Widgets displayed in the header'],
            ['name' => 'Footer Widget Area', 'slug' => 'footer', 'description' => 'Widgets displayed in the footer'],
            ['name' => 'Sidebar Widget Area', 'slug' => 'sidebar', 'description' => 'Widgets displayed in the sidebar'],
        ];

        foreach ($defaultAreas as $area) {
            WidgetArea::firstOrCreate(
                ['slug' => $area['slug']],
                ['name' => $area['name'], 'description' => $area['description']]
            );
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Widget::query()->when($this->selectedAreaId, function ($query) {
                $query->where('widget_area_id', $this->selectedAreaId);
            }))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'info',
                        'html' => 'warning',
                        'menu' => 'success',
                        'recent_posts' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('widgetArea.name')
                    ->label('Area')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->label('Order'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('widget_area_id')
                    ->label('Widget Area')
                    ->options(WidgetArea::pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'html' => 'HTML',
                        'menu' => 'Menu',
                        'recent_posts' => 'Recent Posts',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form(self::getWidgetForm()),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('move_up')
                    ->icon('heroicon-o-arrow-up')
                    ->action(function (Widget $record) {
                        $record->moveUp();
                        Notification::make()
                            ->title('Widget moved up')
                            ->success()
                            ->send();
                    })
                    ->hidden(fn (Widget $record) => $record->order <= 1),
                Tables\Actions\Action::make('move_down')
                    ->icon('heroicon-o-arrow-down')
                    ->action(function (Widget $record) {
                        $record->moveDown();
                        Notification::make()
                            ->title('Widget moved down')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form(self::getWidgetForm()),
            ])
            ->defaultSort('order');
    }

    public static function getWidgetForm(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('widget_area_id')
                ->label('Widget Area')
                ->options(WidgetArea::pluck('name', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\Select::make('type')
                ->options([
                    'text' => 'Text',
                    'html' => 'HTML',
                    'menu' => 'Menu',
                    'recent_posts' => 'Recent Posts',
                ])
                ->required()
                ->reactive(),
            Forms\Components\Textarea::make('settings.content')
                ->label('Content')
                ->rows(5)
                ->visible(fn ($get) => in_array($get('type'), ['text', 'html']))
                ->required(fn ($get) => in_array($get('type'), ['text', 'html'])),
            Forms\Components\Select::make('settings.menu_location')
                ->label('Menu Location')
                ->options([
                    'primary' => 'Primary Navigation',
                    'footer' => 'Footer Navigation',
                ])
                ->visible(fn ($get) => $get('type') === 'menu')
                ->required(fn ($get) => $get('type') === 'menu'),
            Forms\Components\TextInput::make('settings.posts_count')
                ->label('Number of Posts')
                ->numeric()
                ->default(5)
                ->minValue(1)
                ->maxValue(20)
                ->visible(fn ($get) => $get('type') === 'recent_posts')
                ->required(fn ($get) => $get('type') === 'recent_posts'),
            Forms\Components\TextInput::make('order')
                ->numeric()
                ->default(0)
                ->minValue(0)
                ->helperText('Lower numbers appear first'),
            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }

    public function getWidgetAreas()
    {
        return WidgetArea::with(['widgets' => function ($query) {
            $query->where('is_active', true)->orderBy('order');
        }])->get();
    }

    public function selectArea(int $areaId): void
    {
        $this->selectedAreaId = $areaId;
    }
}
