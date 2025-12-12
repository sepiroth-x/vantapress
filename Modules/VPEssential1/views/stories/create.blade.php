@extends('layouts.app')

@section('title', 'Create Story')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Create Story</h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <form action="{{ route('social.stories.store') }}" method="POST" enctype="multipart/form-data" x-data="{ storyType: 'image' }">
            @csrf

            {{-- Story Type --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Story Type</label>
                <div class="flex gap-4">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="image" x-model="storyType" class="sr-only peer">
                        <div class="flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Image
                        </div>
                    </label>

                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="video" x-model="storyType" class="sr-only peer">
                        <div class="flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Video
                        </div>
                    </label>

                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="type" value="text" x-model="storyType" class="sr-only peer">
                        <div class="flex items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Text
                        </div>
                    </label>
                </div>
            </div>

            {{-- Image/Video Upload --}}
            <div x-show="storyType === 'image' || storyType === 'video'" class="mb-6">
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Upload <span x-text="storyType === 'image' ? 'Image' : 'Video'"></span>
                </label>
                <input type="file" name="media" accept="image/*,video/*" 
                       class="block w-full text-sm text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none">
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max size: 10MB</p>
            </div>

            {{-- Text Content --}}
            <div x-show="storyType === 'text'" class="mb-6">
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">Text Content</label>
                <textarea name="content" rows="5" 
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                          placeholder="Share your thoughts..."></textarea>
                
                <label class="block text-sm font-medium text-gray-900 dark:text-white mt-4 mb-2">Background Color</label>
                <div class="grid grid-cols-6 gap-2">
                    @foreach(['#1877f2', '#42b72a', '#e4405f', '#ffd700', '#ff6347', '#9b59b6'] as $color)
                        <label class="cursor-pointer">
                            <input type="radio" name="background_color" value="{{ $color }}" class="sr-only peer">
                            <div class="w-full h-12 rounded-lg border-2 border-transparent peer-checked:border-white peer-checked:ring-2 peer-checked:ring-blue-500" 
                                 style="background-color: {{ $color }}"></div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Duration --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                    Duration (seconds)
                </label>
                <input type="number" name="duration" value="5" min="3" max="15" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            {{-- Submit --}}
            <div class="flex gap-4">
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                    Post Story
                </button>
                <a href="{{ route('social.newsfeed') }}" 
                   class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-semibold text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
