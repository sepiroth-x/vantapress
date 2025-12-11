<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <x-filament::card>
                    <h3 class="text-lg font-semibold mb-4">Widget Areas</h3>
                    @foreach($this->getWidgetAreas() as $area)
                        <div class="p-3 mb-2 rounded-lg border cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800"
                             wire:click="selectArea({{ $area->id }})"
                             class="{{ $selectedAreaId === $area->id ? 'bg-primary-50 dark:bg-primary-900 border-primary-500' : '' }}">
                            <div class="font-medium">{{ $area->name }}</div>
                            <div class="text-sm text-gray-500">{{ $area->widgets_count }} widgets</div>
                        </div>
                    @endforeach
                </x-filament::card>
            </div>
            
            <div class="lg:col-span-2">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
