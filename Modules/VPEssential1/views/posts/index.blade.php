@extends('layouts.app')

@section('title', 'Newsfeed')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Newsfeed</h1>

    {{-- Create Post Form --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
        <form action="{{ route('social.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="flex items-start gap-4">
                {{-- User Avatar --}}
                @if(auth()->user()->profile && auth()->user()->profile->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->profile->avatar) }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-10 h-10 rounded-full object-cover">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif

                {{-- Post Content --}}
                <div class="flex-1">
                    <textarea name="content" 
                              rows="3" 
                              placeholder="What's on your mind?"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                              required></textarea>
                    
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Post Options --}}
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex gap-2">
                            <label class="cursor-pointer px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                                📷 Photo
                                <input type="file" name="media[]" multiple accept="image/*" class="hidden">
                            </label>
                            
                            <select name="visibility" 
                                    class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg border-none">
                                <option value="public">🌍 Public</option>
                                <option value="friends">👥 Friends</option>
                                <option value="private">🔒 Private</option>
                            </select>
                        </div>

                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Post
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Posts Feed --}}
    @forelse($posts as $post)
        @include('vpessential1::components.post-card', ['post' => $post])
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
            <p class="text-gray-600 dark:text-gray-400 mb-4">No posts to show yet.</p>
            <p class="text-sm text-gray-500 dark:text-gray-500">
                Follow some friends to see their posts here!
            </p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Auto-expand textarea
document.querySelector('textarea[name="content"]').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});
</script>
@endpush
