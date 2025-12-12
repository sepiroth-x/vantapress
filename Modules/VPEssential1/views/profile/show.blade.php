@extends('layouts.app')

@section('title', $user->name . ' - Profile')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        {{-- No left sidebar on profile --}}
        
        <main class="col-span-12 lg:col-span-9">
    {{-- Cover Image --}}
    <div class="relative bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-lg h-64">
        @if($profile->cover_image)
            <img src="{{ asset('storage/' . $profile->cover_image) }}" 
                 alt="Cover" 
                 class="w-full h-full object-cover rounded-t-lg">
        @endif
    </div>

    {{-- Profile Header --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-b-lg -mt-16 relative">
        <div class="px-6 pb-6">
            {{-- Avatar --}}
            <div class="flex items-end -mt-16 mb-4">
                <div class="relative">
                    @if($profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" 
                             alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 object-cover">
                    @else
                        <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-3xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    {{-- Verification Badge --}}
                    @if($user->isVerified())
                        <span class="absolute bottom-2 right-2 bg-blue-500 text-white rounded-full p-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </div>
                
                {{-- Action Buttons --}}
                <div class="ml-auto flex items-center gap-2">
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('social.profile.edit') }}" 
                           class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                            Edit Profile
                        </a>
                    @else
                        @if(auth()->user()->isFriendsWith($user->id))
                            <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                                ✓ Friends
                            </span>
                        @else
                            <form action="{{ route('social.friends.request', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Add Friend
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('social.messages.create', $user->id) }}" 
                           class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                            Message
                        </a>
                    @endif
                </div>
            </div>

            {{-- User Info --}}
            <div class="mb-4">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    {{ $profile->display_name ?? $user->name }}
                    @if($user->verification_status === 'verified')
                        <span class="text-blue-500" title="Verified Account">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif
                </h1>
                <p class="text-gray-600 dark:text-gray-400 text-lg">{{ '@' . ($user->username ?? $user->name) }}</p>
                @if($profile->privacy && $profile->privacy !== 'public')
                    <span class="inline-block mt-1 text-xs px-2 py-1 rounded-full {{ $profile->privacy === 'private' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $profile->privacy === 'private' ? '🔒 Private Profile' : '👥 Friends Only' }}
                    </span>
                @endif
            </div>

            @if($profile->bio)
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $profile->bio }}</p>
            @endif

            {{-- Stats --}}
            <div class="flex gap-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $user->posts()->count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $user->friends()->count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Friends</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $user->followers()->count() }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Followers</div>
                </div>
            </div>

            {{-- Social Links --}}
            @if($profile->website || $profile->twitter || $profile->github || $profile->linkedin)
                <div class="flex gap-4 py-4 border-t border-gray-200 dark:border-gray-700">
                    @if($profile->website)
                        <a href="{{ $profile->website }}" target="_blank" class="text-blue-600 hover:underline">
                            🌐 Website
                        </a>
                    @endif
                    @if($profile->twitter)
                        <a href="https://twitter.com/{{ $profile->twitter }}" target="_blank" class="text-blue-400 hover:underline">
                            🐦 Twitter
                        </a>
                    @endif
                    @if($profile->github)
                        <a href="https://github.com/{{ $profile->github }}" target="_blank" class="text-gray-900 dark:text-white hover:underline">
                            💻 GitHub
                        </a>
                    @endif
                    @if($profile->linkedin)
                        <a href="https://linkedin.com/in/{{ $profile->linkedin }}" target="_blank" class="text-blue-700 hover:underline">
                            💼 LinkedIn
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- User Posts --}}
    <div class="mt-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Posts</h2>
        @forelse($user->posts()->latest()->paginate(10) as $post)
            @include('vpessential1::components.post-card', ['post' => $post])
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <p class="text-gray-600 dark:text-gray-400">No posts yet.</p>
            </div>
        @endforelse
    </div>
        </main>
        
        {{-- Right sidebar with 3 columns on profile --}}
        <aside class="hidden lg:block lg:col-span-3">
            @include('vpessential1::components.sidebar-right')
        </aside>
    </div>
</div>
@endsection
