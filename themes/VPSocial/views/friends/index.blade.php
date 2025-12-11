@extends('layouts.app')

@section('title', 'Friends')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Friends</h1>
        <a href="{{ route('social.friends.requests') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Friend Requests
        </a>
    </div>

    {{-- Friends Grid --}}
    @if($friends->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($friends as $friend)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center gap-4 mb-4">
                        {{-- Avatar --}}
                        @if($friend->profile && $friend->profile->avatar)
                            <img src="{{ asset('storage/' . $friend->profile->avatar) }}" 
                                 alt="{{ $friend->name }}" 
                                 class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xl font-bold text-white">
                                {{ strtoupper(substr($friend->name, 0, 1)) }}
                            </div>
                        @endif

                        <div class="flex-1">
                            <a href="{{ route('social.profile.user', $friend->id) }}" 
                               class="font-semibold text-gray-900 dark:text-white hover:underline">
                                {{ $friend->profile->display_name ?? $friend->name }}
                            </a>
                            @if($friend->isVerified())
                                <span class="text-blue-500">✓</span>
                            @endif
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ '@' . $friend->name }}
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <a href="{{ route('social.profile.user', $friend->id) }}" 
                           class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            View Profile
                        </a>
                        <a href="{{ route('social.messages.create', $friend->id) }}" 
                           class="flex-1 text-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm">
                            Message
                        </a>
                    </div>

                    {{-- Remove Friend --}}
                    <form action="{{ route('social.friends.remove', $friend->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Remove friend?')"
                                class="w-full px-4 py-2 text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                            Remove Friend
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4">You don't have any friends yet.</p>
            <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                Start connecting with people to build your network!
            </p>
            <a href="{{ route('social.newsfeed') }}" 
               class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Explore
            </a>
        </div>
    @endif
</div>
@endsection
