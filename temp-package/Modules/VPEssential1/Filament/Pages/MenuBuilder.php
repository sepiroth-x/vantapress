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
use Modules\VPEssential1\Models\Menu;
use Modules\VPEssential1\Models\MenuItem;
use Filament\Notifications\Notification;

class MenuBuilder extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationLabel = 'Menu Builder';
    protected static ?string $navigationGroup = 'VP Essential';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'VPEssential1::filament.pages.menu-builder';

    public ?int $selectedMenuId = null;
    public ?array $menuItems = [];

    public function mount(): void
    {
        $firstMenu = Menu::first();
        if ($firstMenu) {
            $this->selectedMenuId = $firstMenu->id;
            $this->loadMenuItems();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Menu::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'primary' => 'success',
                        'footer' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options([
                        'primary' => 'Primary',
                        'footer' => 'Footer',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('edit_items')
                    ->label('Edit Items')
                    ->icon('heroicon-o-pencil-square')
                    ->action(function (Menu $record) {
                        $this->selectedMenuId = $record->id;
                        $this->loadMenuItems();
                    }),
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('location')
                            ->options([
                                'primary' => 'Primary Navigation',
                                'footer' => 'Footer Navigation',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500),
                    ]),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->form([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('location')
                            ->options([
                                'primary' => 'Primary Navigation',
                                'footer' => 'Footer Navigation',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(500),
                    ]),
            ]);
    }

    public function getMenuItemsProperty()
    {
        if (!$this->selectedMenuId) {
            return [];
        }

        return MenuItem::where('menu_id', $this->selectedMenuId)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    protected function loadMenuItems(): void
    {
        if (!$this->selectedMenuId) {
            $this->menuItems = [];
            return;
        }

        $this->menuItems = MenuItem::where('menu_id', $this->selectedMenuId)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    public function addMenuItem(): void
    {
        if (!$this->selectedMenuId) {
            Notification::make()
                ->title('Please select a menu first')
                ->warning()
                ->send();
            return;
        }

        $maxOrder = MenuItem::where('menu_id', $this->selectedMenuId)->max('order') ?? 0;

        MenuItem::create([
            'menu_id' => $this->selectedMenuId,
            'title' => 'New Item',
            'url' => '#',
            'order' => $maxOrder + 1,
        ]);

        $this->loadMenuItems();

        Notification::make()
            ->title('Menu item added')
            ->success()
            ->send();
    }

    public function updateMenuItem(int $itemId, array $data): void
    {
        $item = MenuItem::find($itemId);
        if ($item) {
            $item->update($data);
            $this->loadMenuItems();

            Notification::make()
                ->title('Menu item updated')
                ->success()
                ->send();
        }
    }

    public function deleteMenuItem(int $itemId): void
    {
        MenuItem::destroy($itemId);
        $this->loadMenuItems();

        Notification::make()
            ->title('Menu item deleted')
            ->success()
            ->send();
    }

    public function reorderItems(array $newOrder): void
    {
        foreach ($newOrder as $index => $itemId) {
            MenuItem::where('id', $itemId)->update(['order' => $index]);
        }

        $this->loadMenuItems();

        Notification::make()
            ->title('Menu items reordered')
            ->success()
            ->send();
    }

    public function getSelectedMenu()
    {
        if (!$this->selectedMenuId) {
            return null;
        }
        return Menu::find($this->selectedMenuId);
    }
}
