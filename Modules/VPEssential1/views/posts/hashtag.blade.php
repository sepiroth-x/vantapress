@extends('vpessential1::layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    {{-- Hashtag Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg shadow-lg p-8 mb-6">
        <h1 class="text-4xl font-bold mb-2">#{{ $tag }}</h1>
        <p class="text-blue-100">{{ $posts->count() }} {{ Str::plural('post', $posts->count()) }} found</p>
    </div>

    {{-- Posts with this hashtag --}}
    @if($posts->count() > 0)
        @foreach($posts as $post)
            @include('vpessential1::components.post-card', ['post' => $post])
        @endforeach
    @else
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-12 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No posts found</h2>
            <p class="text-gray-600 dark:text-gray-400">Be the first to post with #{{ $tag }}!</p>
            <a href="{{ route('social.newsfeed') }}" 
               class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Go to Newsfeed
            </a>
        </div>
    @endif
</div>
@endsection
