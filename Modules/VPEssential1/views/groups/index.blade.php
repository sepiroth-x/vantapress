@extends('layouts.app')

@section('title', 'Groups & Communities')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        @include('vpessential1::components.sidebar-left')
        
        <main class="col-span-12 lg:col-span-6">
            {{-- Header with Search --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">🏘️ Groups & Communities</h1>
                        <p class="text-gray-600 dark:text-gray-400">Discover and join communities of like-minded people</p>
                    </div>
                    <a href="{{ route('social.groups.create') }}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold whitespace-nowrap">
                        ➕ Create Group
                    </a>
                </div>
                
                {{-- Search Bar --}}
                <div class="mt-6">
                    <form method="GET" action="{{ route('social.groups.index') }}" class="flex gap-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search groups..."
                               class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <button type="submit" 
                                class="px-6 py-2 bg-gray-800 dark:bg-gray-600 text-white rounded-lg hover:bg-gray-900 dark:hover:bg-gray-500">
                            🔍 Search
                        </button>
                    </form>
                </div>
                
                {{-- Filter Tabs --}}
                <div class="flex gap-2 mt-4 overflow-x-auto pb-2">
                    <a href="{{ route('social.groups.index') }}" 
                       class="px-4 py-2 rounded-lg whitespace-nowrap {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        🌟 All Groups
                    </a>
                    <a href="{{ route('social.groups.index', ['filter' => 'my']) }}" 
                       class="px-4 py-2 rounded-lg whitespace-nowrap {{ request('filter') === 'my' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        👤 My Groups
                    </a>
                    <a href="{{ route('social.groups.index', ['filter' => 'popular']) }}" 
                       class="px-4 py-2 rounded-lg whitespace-nowrap {{ request('filter') === 'popular' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        🔥 Popular
                    </a>
                    <a href="{{ route('social.groups.index', ['filter' => 'new']) }}" 
                       class="px-4 py-2 rounded-lg whitespace-nowrap {{ request('filter') === 'new' ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        ✨ New
                    </a>
                </div>
            </div>

            {{-- My Groups (if filter is 'my' or default) --}}
            @if(!request('filter') || request('filter') === 'my')
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        @if(request('filter') === 'my') Your Groups @else My Groups @endif
                    </h2>
                    @if($myGroups->count() > 0)
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($myGroups as $group)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                                    <div class="flex flex-col sm:flex-row">
                                        @if($group->cover_image)
                                            <img src="{{ asset('storage/' . $group->cover_image) }}" 
                                                 class="w-full sm:w-48 h-32 object-cover">
                                        @else
                                            <div class="w-full sm:w-48 h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                                        @endif
                                        
                                        <div class="flex-1 p-4">
                                            <div class="flex items-start gap-3">
                                                @if($group->avatar)
                                                    <img src="{{ asset('storage/' . $group->avatar) }}" 
                                                         class="w-16 h-16 rounded-lg object-cover">
                                                @else
                                                    <div class="w-16 h-16 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                                        {{ strtoupper(substr($group->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                                
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white flex items-center gap-2">
                                                        {{ $group->name }}
                                                        @if($group->privacy === 'private')
                                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">🔒 Private</span>
                                                        @elseif($group->privacy === 'secret')
                                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded">🔐 Secret</span>
                                                        @endif
                                                    </h3>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                        {{ number_format($group->members_count) }} members
                                                    </p>
                                                    @if($group->description)
                                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 line-clamp-2">
                                                            {{ Str::limit($group->description, 120) }}
                                                        </p>
                                                    @endif
                                                </div>
                                                
                                                <a href="{{ route('social.groups.show', $group->slug) }}" 
                                                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm whitespace-nowrap">
                                                    View Group
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                            <div class="text-6xl mb-4">🏘️</div>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 text-lg">You haven't joined any groups yet.</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                                Groups are a great way to connect with people who share your interests!
                            </p>
                            <a href="{{ route('social.groups.create') }}" 
                               class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                                Create Your First Group
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Suggested/All Groups --}}
            @if($suggestedGroups->count() > 0)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        @if(request('filter') === 'popular') 🔥 Popular Groups
                        @elseif(request('filter') === 'new') ✨ New Groups
                        @elseif(request('search')) 🔍 Search Results
                        @else 🌟 Discover Groups
                        @endif
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($suggestedGroups as $group)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                                @if($group->cover_image)
                                    <img src="{{ asset('storage/' . $group->cover_image) }}" 
                                         class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gradient-to-r from-green-500 to-teal-600"></div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        @if($group->avatar)
                                            <img src="{{ asset('storage/' . $group->avatar) }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($group->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $group->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($group->members_count) }} members
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($group->description)
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 line-clamp-2">
                                            {{ Str::limit($group->description, 80) }}
                                        </p>
                                    @endif
                                    
                                    <div class="flex gap-2">
                                        <a href="{{ route('social.groups.show', $group->slug) }}" 
                                           class="flex-1 text-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 text-sm">
                                            View
                                        </a>
                                        <form action="{{ route('social.groups.join', $group->slug) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">
                                                Join
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $suggestedGroups->links() }}
                    </div>
                </div>
            @else
                @if(request('search'))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                        <div class="text-6xl mb-4">🔍</div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 text-lg">No groups found matching "{{ request('search') }}"</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500 mb-6">
                            Try a different search term or create your own group!
                        </p>
                        <a href="{{ route('social.groups.create') }}" 
                           class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                            Create Group
                        </a>
                    </div>
                @endif
            @endif
        </main>
        
        @include('vpessential1::components.sidebar-right')
    </div>
</div>
@endsection
