<div>
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6 flex gap-3">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>
</div>
