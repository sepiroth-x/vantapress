@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        @include('vpessential1::components.sidebar-left')
        
        <main class="col-span-12 lg:col-span-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Messages</h1>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        @if($conversations->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($conversations as $conversation)
                    @php
                        $otherParticipant = $conversation->participants
                            ->where('user_id', '!=', auth()->id())
                            ->first();
                        $lastMessage = $conversation->lastMessage;
                    @endphp
                    
                    <a href="javascript:void(0)" 
                       @click="window.dispatchEvent(new CustomEvent('open-chat-{{ $conversation->id }}'))"
                       class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer">
                        {{-- Avatar --}}
                        @if($otherParticipant && $otherParticipant->user->profile && $otherParticipant->user->profile->avatar)
                            <img src="{{ asset('storage/' . $otherParticipant->user->profile->avatar) }}" 
                                 alt="{{ $otherParticipant->user->name }}" 
                                 class="w-14 h-14 rounded-full object-cover">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-lg font-bold text-white">
                                {{ $otherParticipant ? strtoupper(substr($otherParticipant->user->name, 0, 1)) : '?' }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                    {{ $otherParticipant ? ($otherParticipant->user->profile->display_name ?? $otherParticipant->user->name) : 'Unknown' }}
                                </h3>
                                @if($lastMessage)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $lastMessage->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            @if($lastMessage)
                                <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                    {{ $lastMessage->user_id === auth()->id() ? 'You: ' : '' }}
                                    {{ $lastMessage->content }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-500 italic">
                                    No messages yet
                                </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="p-8 text-center">
                <p class="text-gray-600 dark:text-gray-400 mb-4">No conversations yet.</p>
                <p class="text-sm text-gray-500 dark:text-gray-500">
                    Start a conversation by visiting someone's profile!
                </p>
            </div>
        @endif
    </div>
        </main>
        
        @include('vpessential1::components.sidebar-right')
    </div>
</div>

{{-- Include chat boxes for all conversations --}}
@foreach($conversations as $conversation)
    @php
        $otherParticipant = $conversation->participants
            ->where('user_id', '!=', auth()->id())
            ->first();
    @endphp
    @include('vpessential1::components.chat-box', [
        'conversation' => $conversation,
        'otherParticipant' => $otherParticipant
    ])
@endforeach
@endsection
