@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Edit Profile</h1>

        <form action="{{ route('social.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Profile Picture
                </label>
                <div class="flex items-center gap-4">
                    @if($profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}" 
                             alt="Avatar" 
                             class="w-20 h-20 rounded-full object-cover">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-2xl font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <input type="file" 
                           name="avatar" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                @error('avatar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cover Image --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Cover Photo
                </label>
                @if($profile->cover_image)
                    <img src="{{ asset('storage/' . $profile->cover_image) }}" 
                         alt="Cover" 
                         class="w-full h-40 object-cover rounded-lg mb-2">
                @endif
                <input type="file" 
                       name="cover_image" 
                       accept="image/*"
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Display Name --}}
            <div class="mb-6">
                <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Display Name
                </label>
                <input type="text" 
                       name="display_name" 
                       id="display_name" 
                       value="{{ old('display_name', $profile->display_name ?? auth()->user()->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                @error('display_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Username --}}
            <div class="mb-6">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Username
                    <span class="text-sm text-gray-500">(used for login)</span>
                </label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-r-0 border-gray-300 rounded-l-lg dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                        @
                    </span>
                    <input type="text" 
                           name="username" 
                           id="username" 
                           value="{{ old('username', auth()->user()->username) }}"
                           placeholder="johndoe"
                           pattern="[a-zA-Z0-9_]+"
                           class="rounded-none rounded-r-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>
                <p class="mt-1 text-xs text-gray-500">Alphanumeric and underscores only. You can login with either username or email.</p>
                @error('username')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bio --}}
            <div class="mb-6">
                <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Bio
                </label>
                <textarea name="bio" 
                          id="bio" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">{{ old('bio', $profile->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Location
                </label>
                <input type="text" 
                       name="location" 
                       id="location" 
                       value="{{ old('location', $profile->location) }}"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Website --}}
            <div class="mb-6">
                <label for="website" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Website
                </label>
                <input type="url" 
                       name="website" 
                       id="website" 
                       value="{{ old('website', $profile->website) }}"
                       placeholder="https://example.com"
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                @error('website')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Social Links --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="twitter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Twitter
                    </label>
                    <input type="text" 
                           name="twitter" 
                           id="twitter" 
                           value="{{ old('twitter', $profile->twitter) }}"
                           placeholder="username"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="github" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        GitHub
                    </label>
                    <input type="text" 
                           name="github" 
                           id="github" 
                           value="{{ old('github', $profile->github) }}"
                           placeholder="username"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="linkedin" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        LinkedIn
                    </label>
                    <input type="text" 
                           name="linkedin" 
                           id="linkedin" 
                           value="{{ old('linkedin', $profile->linkedin) }}"
                           placeholder="username"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex gap-4">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Save Changes
                </button>
                <a href="{{ route('social.profile.show') }}" 
                   class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
