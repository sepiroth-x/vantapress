@extends('layouts.app')

@section('title', 'Newsfeed')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="grid grid-cols-12 gap-6">
        
        {{-- LEFT SIDEBAR --}}
        @include('vpessential1::components.sidebar-left')
        
        {{-- MAIN CONTENT --}}
        <main class="col-span-12 lg:col-span-6">
            {{-- Stories Section --}}
            @include('vpessential1::components.stories-bar')

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

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
                    <div class="relative" x-data="mentionAutocomplete()">
                        <textarea name="content" 
                                  id="post-content"
                                  rows="3" 
                                  placeholder="What's on your mind? Use @ to mention someone..."
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                  @input="handleInput($event)"
                                  @keydown="handleKeydown($event)"
                                  required></textarea>
                        
                        {{-- Mention Suggestions Dropdown --}}
                        <div x-show="showSuggestions" 
                             x-transition
                             class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                             @click.outside="showSuggestions = false">
                            <template x-for="(user, index) in filteredUsers" :key="user.id">
                                <div @click="selectUser(user)"
                                     @mouseenter="selectedIndex = index"
                                     :class="{'bg-blue-50 dark:bg-blue-900/20': selectedIndex === index}"
                                     class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                                    <img :src="user.avatar" :alt="user.name" class="w-8 h-8 rounded-full">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="user.name"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400" x-text="'@' + user.username"></p>
                                    </div>
                                </div>
                            </template>
                            <div x-show="filteredUsers.length === 0" class="px-4 py-3 text-center text-gray-600 dark:text-gray-400 text-sm">
                                No users found
                            </div>
                        </div>
                    </div>
                    
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
        </main>
        
        {{-- RIGHT SIDEBAR --}}
        @include('vpessential1::components.sidebar-right')
        
    </div>
</div>

@push('scripts')
<script>
function mentionAutocomplete() {
    return {
        showSuggestions: false,
        filteredUsers: [],
        selectedIndex: -1,
        mentionStart: -1,
        searchQuery: '',
        
        async handleInput(event) {
            const textarea = event.target;
            const cursorPos = textarea.selectionStart;
            const text = textarea.value;
            
            // Find the last @ symbol before cursor
            const beforeCursor = text.substring(0, cursorPos);
            const lastAtPos = beforeCursor.lastIndexOf('@');
            
            if (lastAtPos !== -1) {
                const afterAt = beforeCursor.substring(lastAtPos + 1);
                
                // Check if there's a space after @ (which would end the mention)
                if (afterAt.includes(' ') || afterAt.includes('\n')) {
                    this.showSuggestions = false;
                    return;
                }
                
                this.searchQuery = afterAt;
                this.mentionStart = lastAtPos;
                
                if (this.searchQuery.length >= 0) {
                    await this.fetchUsers(this.searchQuery);
                    this.showSuggestions = true;
                    this.selectedIndex = 0;
                }
            } else {
                this.showSuggestions = false;
            }
        },
        
        async fetchUsers(query) {
            try {
                const response = await fetch(`/api/users/search?q=${encodeURIComponent(query)}&limit=5`);
                const data = await response.json();
                this.filteredUsers = data.users || [];
            } catch (error) {
                console.error('Error fetching users:', error);
                this.filteredUsers = [];
            }
        },
        
        selectUser(user) {
            const textarea = document.getElementById('post-content');
            const text = textarea.value;
            
            // Replace from @ to cursor position with @username
            const before = text.substring(0, this.mentionStart);
            const after = text.substring(textarea.selectionStart);
            
            textarea.value = before + '@' + user.username + ' ' + after;
            
            // Set cursor after mention
            const newPos = this.mentionStart + user.username.length + 2;
            textarea.setSelectionRange(newPos, newPos);
            textarea.focus();
            
            this.showSuggestions = false;
            this.selectedIndex = -1;
        },
        
        handleKeydown(event) {
            if (!this.showSuggestions) return;
            
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.selectedIndex = Math.min(this.selectedIndex + 1, this.filteredUsers.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.selectedIndex = Math.max(this.selectedIndex - 1, 0);
            } else if (event.key === 'Enter' && this.selectedIndex >= 0) {
                event.preventDefault();
                this.selectUser(this.filteredUsers[this.selectedIndex]);
            } else if (event.key === 'Escape') {
                this.showSuggestions = false;
            }
        }
    }
}

// Auto-expand textarea
const textarea = document.querySelector('textarea[name="content"]');
if (textarea) {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}
</script>
@endpush
@endsection
