@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    @php
        $otherParticipant = $conversation->participants
            ->where('user_id', '!=', auth()->id())
            ->first();
    @endphp

    {{-- Chat Header --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-t-lg p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('social.messages.index') }}" 
               class="text-blue-600 hover:underline">
                ← Back
            </a>
            @if($otherParticipant)
                <a href="{{ route('social.profile.user', $otherParticipant->user_id) }}" 
                   class="flex items-center gap-3">
                    @if($otherParticipant->user->profile && $otherParticipant->user->profile->avatar)
                        <img src="{{ asset('storage/' . $otherParticipant->user->profile->avatar) }}" 
                             alt="{{ $otherParticipant->user->name }}" 
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                            {{ strtoupper(substr($otherParticipant->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h2 class="font-semibold text-gray-900 dark:text-white">
                            {{ $otherParticipant->user->profile->display_name ?? $otherParticipant->user->name }}
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ '@' . $otherParticipant->user->name }}
                        </p>
                    </div>
                </a>
            @endif
        </div>
    </div>

    {{-- Messages Container --}}
    <div class="bg-gray-50 dark:bg-gray-900 p-6 space-y-4" style="height: 500px; overflow-y: auto;" id="messages-container">
        @forelse($messages as $message)
            <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs lg:max-w-md">
                    @if($message->user_id !== auth()->id())
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                            {{ $message->user->name }}
                        </p>
                    @endif
                    <div class="px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white' }}">
                        <p>{{ $message->content }}</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ $message->created_at->format('g:i A') }}
                    </p>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                <p>No messages yet. Start the conversation!</p>
            </div>
        @endforelse
    </div>

    {{-- Message Input --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-b-lg p-4">
        <form action="{{ route('social.messages.send', $conversation->id) }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" 
                   name="content" 
                   placeholder="Type a message..."
                   class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                   required
                   autofocus>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                Send
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Scroll to bottom on load
const container = document.getElementById('messages-container');
container.scrollTop = container.scrollHeight;
</script>
@endpush
@endsection
