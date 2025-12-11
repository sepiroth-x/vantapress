@extends('layouts.app')

@section('title', 'Friend Requests')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Friend Requests</h1>
        <a href="{{ route('social.friends.index') }}" 
           class="text-blue-600 hover:underline">
            ← Back to Friends
        </a>
    </div>

    @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $request)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            {{-- Avatar --}}
                            @if($request->user->profile && $request->user->profile->avatar)
                                <img src="{{ asset('storage/' . $request->user->profile->avatar) }}" 
                                     alt="{{ $request->user->name }}" 
                                     class="w-16 h-16 rounded-full object-cover">
                            @else
                                <div class="w-16 h-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-xl font-bold text-white">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div>
                                <a href="{{ route('social.profile.user', $request->user_id) }}" 
                                   class="font-semibold text-gray-900 dark:text-white hover:underline">
                                    {{ $request->user->profile->display_name ?? $request->user->name }}
                                </a>
                                @if($request->user->isVerified())
                                    <span class="text-blue-500">✓</span>
                                @endif
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ '@' . $request->user->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    {{ $request->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <form action="{{ route('social.friends.accept', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    Accept
                                </button>
                            </form>
                            <form action="{{ route('social.friends.reject', $request->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="px-6 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4">No pending friend requests.</p>
        </div>
    @endif
</div>
@endsection
