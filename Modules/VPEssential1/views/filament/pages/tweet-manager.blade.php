<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $stats = $this->getTweetStats();
            @endphp
            <x-filament::card>
                <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-500">Total Tweets</div>
            </x-filament::card>
            <x-filament::card>
                <div class="text-2xl font-bold">{{ $stats['today'] }}</div>
                <div class="text-sm text-gray-500">Today</div>
            </x-filament::card>
            <x-filament::card>
                <div class="text-2xl font-bold">{{ $stats['replies'] }}</div>
                <div class="text-sm text-gray-500">Replies</div>
            </x-filament::card>
            <x-filament::card>
                <div class="text-2xl font-bold">{{ $stats['retweets'] }}</div>
                <div class="text-sm text-gray-500">Retweets</div>
            </x-filament::card>
        </div>
        
        {{ $this->table }}
    </div>
</x-filament-panels::page>
