@extends('layouts.app')

@section('title', 'Friends')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        @include('vpessential1::components.sidebar-left')
        
        <main class="col-span-12 lg:col-span-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Friends</h1>
        <a href="{{ route('social.friends.requests') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Friend Requests
        </a>
    </div>

    {{-- Friends Grid --}}
    @if($friends->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($friends as $friend)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300">
                    {{-- Friend Header with Cover --}}
                    <div class="h-24 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                    
                    {{-- Friend Info --}}
                    <div class="relative px-6 pb-6">
                        {{-- Avatar (overlapping cover) --}}
                        <div class="absolute -top-12">
                            @if($friend->profile && $friend->profile->avatar)
                                <img src="{{ asset('storage/' . $friend->profile->avatar) }}" 
                                     alt="{{ $friend->name }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg">
                            @else
                                <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-3xl font-bold text-white border-4 border-white dark:border-gray-800 shadow-lg">
                                    {{ strtoupper(substr($friend->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="pt-14">
                            {{-- Name and Username --}}
                            <div class="mb-4">
                                <a href="{{ route('social.profile.user', $friend->id) }}" 
                                   class="font-bold text-xl text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    {{ $friend->profile->display_name ?? $friend->name }}
                                    @if($friend->isVerified())
                                        <span class="text-blue-500 text-sm">✓</span>
                                    @endif
                                </a>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ '@' . $friend->name }}
                                </p>
                                
                                {{-- Bio if exists --}}
                                @if($friend->profile && $friend->profile->bio)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                                        {{ $friend->profile->bio }}
                                    </p>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2 mb-3">
                                <a href="{{ route('social.profile.user', $friend->id) }}" 
                                   class="flex-1 text-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium shadow-md hover:shadow-lg transition-all">
                                    👤 Profile
                                </a>
                                <a href="{{ route('social.messages.create', $friend->id) }}" 
                                   class="flex-1 text-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 font-medium shadow-md hover:shadow-lg transition-all">
                                    💬 Message
                                </a>
                            </div>

                            {{-- Remove Friend (Less Prominent) --}}
                            <form action="{{ route('social.friends.remove', $friend->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to remove {{ $friend->profile->display_name ?? $friend->name }} from your friends?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full px-3 py-2 text-sm text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-lg transition-all">
                                    ❌ Remove Friend
                                </button>
                            </form>
                        </div>
                    </div>
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
        </main>
        
        @include('vpessential1::components.sidebar-right')
    </div>
</div>
@endsection
