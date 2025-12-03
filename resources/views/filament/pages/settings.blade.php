<div>
    <form wire:submit="save">
        {{ $this->form }}
        
        <div class="mt-6">
            {{ $this->getFormActions() }}
        </div>
    </form>
</div>
