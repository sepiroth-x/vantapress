<!-- Left Sidebar - Quick Access -->
<div class="space-y-4">
    <!-- User Profile Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <a href="{{ route('social.profile', auth()->id()) }}" class="flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded-lg transition">
            <img src="{{ auth()->user()->avatar_url }}" 
                 alt="{{ auth()->user()->name }}" 
                 class="h-10 w-10 rounded-full object-cover">
            <div class="flex-1 min-w-0">
                <p class="font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate">View your profile</p>
            </div>
        </a>
    </div>
    
    <!-- Quick Links -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4">
            <h3 class="font-semibold mb-3">Quick Access</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('social.friends') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Friends</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('social.friends.requests') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Friend Requests</span>
                        @if(auth()->user()->pendingFriendRequests()->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ auth()->user()->pendingFriendRequests()->count() }}
                        </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('social.messages') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        <span>Messages</span>
                        @if(auth()->user()->unreadMessagesCount() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ auth()->user()->unreadMessagesCount() }}
                        </span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('social.notifications') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span>Notifications</span>
                        @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            {{ auth()->user()->unreadNotifications()->count() }}
                        </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
