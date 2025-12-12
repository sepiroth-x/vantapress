@extends('layouts.app')

@section('title', 'Groups & Communities')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        @include('vpessential1::components.sidebar-left')
        
        <main class="col-span-12 lg:col-span-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Groups</h1>
                <a href="{{ route('social.groups.create') }}" 
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Group
                </a>
            </div>

            {{-- My Groups --}}
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">My Groups</h2>
                @if($myGroups->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($myGroups as $group)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                @if($group->cover_image)
                                    <img src="{{ asset('storage/' . $group->cover_image) }}" 
                                         class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($group->avatar)
                                            <img src="{{ asset('storage/' . $group->avatar) }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($group->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 dark:text-white">
                                                {{ $group->name }}
                                                @if($group->is_verified)
                                                    <span class="text-blue-500 text-sm">✓</span>
                                                @endif
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($group->members_count) }} members
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('social.groups.show', $group->slug) }}" 
                                       class="block w-full text-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                        View Group
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $myGroups->links() }}
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                        <p class="text-gray-600 dark:text-gray-400 mb-4">You haven't joined any groups yet.</p>
                        <a href="{{ route('social.groups.create') }}" 
                           class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Your First Group
                        </a>
                    </div>
                @endif
            </div>

            {{-- Suggested Groups --}}
            @if($suggestedGroups->count() > 0)
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Suggested Groups</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($suggestedGroups as $group)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                @if($group->cover_image)
                                    <img src="{{ asset('storage/' . $group->cover_image) }}" 
                                         class="w-full h-32 object-cover">
                                @else
                                    <div class="w-full h-32 bg-gradient-to-r from-green-500 to-teal-600"></div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if($group->avatar)
                                            <img src="{{ asset('storage/' . $group->avatar) }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-green-500 flex items-center justify-center text-white font-bold">
                                                {{ strtoupper(substr($group->name, 0, 2)) }}
                                            </div>
                                        @endif
                                        
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $group->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($group->members_count) }} members
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('social.groups.join', $group->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                            Join Group
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </main>
        
        @include('vpessential1::components.sidebar-right')
    </div>
</div>
@endsection
