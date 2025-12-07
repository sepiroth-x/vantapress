<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1">
                <x-filament::card>
                    <h3 class="text-lg font-semibold mb-4">Menus</h3>
                    @if($this->getSelectedMenu())
                        <div class="mb-4 p-3 bg-primary-50 dark:bg-primary-900 rounded-lg">
                            <div class="font-medium">{{ $this->getSelectedMenu()->name }}</div>
                            <div class="text-sm text-gray-500">{{ $this->getSelectedMenu()->location }}</div>
                        </div>
                    @endif
                    
                    <x-filament::button wire:click="addMenuItem" class="w-full">
                        Add Menu Item
                    </x-filament::button>
                </x-filament::card>
            </div>
            
            <div class="lg:col-span-3">
                {{ $this->table }}
                
                @if($menuItems)
                    <x-filament::card class="mt-6">
                        <h3 class="text-lg font-semibold mb-4">Menu Items</h3>
                        <div class="space-y-2">
                            @foreach($menuItems as $item)
                                <div class="flex items-center gap-4 p-3 border rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $item['title'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $item['url'] }}</div>
                                    </div>
                                    <x-filament::button
                                        wire:click="deleteMenuItem({{ $item['id'] }})"
                                        color="danger"
                                        size="sm">
                                        Delete
                                    </x-filament::button>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::card>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
