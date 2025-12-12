@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Post</h1>
            <a href="{{ route('social.posts.index') }}" 
               class="text-blue-600 hover:underline">
                ← Back to Feed
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('social.posts.update', $post->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Post Content
                    </label>
                    <textarea name="content" 
                              id="content"
                              rows="6" 
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                              required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="visibility" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Visibility
                    </label>
                    <select name="visibility" 
                            id="visibility"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="public" {{ $post->visibility === 'public' ? 'selected' : '' }}>🌍 Public</option>
                        <option value="friends" {{ $post->visibility === 'friends' ? 'selected' : '' }}>👥 Friends</option>
                        <option value="private" {{ $post->visibility === 'private' ? 'selected' : '' }}>🔒 Private</option>
                    </select>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($post->media)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Current Media (cannot be edited)
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($post->media as $media)
                                <img src="{{ asset('storage/' . $media) }}" 
                                     alt="Post media" 
                                     class="rounded-lg w-full h-32 object-cover">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Save Changes
                    </button>
                    <a href="{{ route('social.posts.index') }}" 
                       class="px-6 py-3 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 font-medium">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
