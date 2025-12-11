<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}
        
        <div class="flex justify-end">
            <x-filament::button wire:click="save" type="button">
                Save Settings
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
