@props(['conversation', 'otherParticipant'])

<div x-data="chatBox{{ $conversation->id }}()" 
     class="fixed bottom-0 right-20 w-80 shadow-2xl rounded-t-lg overflow-hidden bg-white dark:bg-gray-800 transition-all duration-300 z-50"
     :style="{ 
         height: isMinimized ? '48px' : '450px',
         display: isOpen ? 'block' : 'none'
     }"
     style="display: none;">
    
    {{-- Chat Header --}}
    <div @click="toggleMinimize()" 
         class="bg-blue-600 dark:bg-blue-700 text-white px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-blue-700 dark:hover:bg-blue-800">
        <div class="flex items-center gap-2">
            @if($otherParticipant && $otherParticipant->user->profile && $otherParticipant->user->profile->avatar)
                <img src="{{ asset('storage/' . $otherParticipant->user->profile->avatar) }}" 
                     alt="{{ $otherParticipant->user->name }}" 
                     class="w-8 h-8 rounded-full object-cover">
            @else
                <div class="w-8 h-8 rounded-full bg-white bg-opacity-30 flex items-center justify-center text-xs font-bold">
                    {{ $otherParticipant ? strtoupper(substr($otherParticipant->user->name, 0, 1)) : '?' }}
                </div>
            @endif
            <span class="font-medium text-sm truncate" style="max-width: 150px;">
                {{ $otherParticipant ? ($otherParticipant->user->profile->display_name ?? $otherParticipant->user->name) : 'Chat' }}
            </span>
        </div>
        
        <div class="flex items-center gap-2">
            <button @click.stop="toggleMinimize()" 
                    class="hover:bg-white hover:bg-opacity-20 rounded p-1">
                <span x-show="!isMinimized">➖</span>
                <span x-show="isMinimized">⬜</span>
            </button>
            <button @click.stop="close()" 
                    class="hover:bg-white hover:bg-opacity-20 rounded p-1">
                ✕
            </button>
        </div>
    </div>
    
    {{-- Messages Container --}}
    <div x-show="!isMinimized" 
         class="bg-gray-50 dark:bg-gray-900 p-4 space-y-3 overflow-y-auto"
         style="height: 350px;"
         x-ref="messagesContainer">
        <template x-for="message in messages" :key="message.id">
            <div :class="message.user_id === {{ auth()->id() }} ? 'flex justify-end' : 'flex justify-start'">
                <div class="max-w-xs">
                    <div :class="message.user_id === {{ auth()->id() }} 
                                 ? 'bg-blue-600 text-white' 
                                 : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white'"
                         class="px-3 py-2 rounded-lg text-sm break-words">
                        <p x-text="message.content"></p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" 
                       x-text="formatTime(message.created_at)"></p>
                </div>
            </div>
        </template>
        
        <div x-show="messages.length === 0" 
             class="text-center text-gray-500 dark:text-gray-400 py-8 text-sm">
            No messages yet. Start the conversation!
        </div>
    </div>
    
    {{-- Message Input --}}
    <div x-show="!isMinimized" 
         class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-3">
        <form @submit.prevent="sendMessage()" class="flex gap-2">
            <input type="text" 
                   x-model="newMessage"
                   placeholder="Type a message..."
                   class="flex-1 px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                   @keydown.enter="sendMessage()">
            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                Send
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function chatBox{{ $conversation->id }}() {
    return {
        isOpen: false,
        isMinimized: false,
        messages: @json($conversation->messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'user_id' => $msg->user_id,
                'content' => $msg->content,
                'created_at' => $msg->created_at->toIso8601String(),
            ];
        })),
        newMessage: '',
        conversationId: {{ $conversation->id }},
        
        init() {
            // Listen for global event to open this chat
            window.addEventListener('open-chat-{{ $conversation->id }}', () => {
                this.open();
            });
            
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },
        
        open() {
            this.isOpen = true;
            this.isMinimized = false;
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },
        
        close() {
            this.isOpen = false;
        },
        
        toggleMinimize() {
            this.isMinimized = !this.isMinimized;
            if (!this.isMinimized) {
                this.$nextTick(() => {
                    this.scrollToBottom();
                });
            }
        },
        
        scrollToBottom() {
            if (this.$refs.messagesContainer) {
                this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
            }
        },
        
        async sendMessage() {
            if (!this.newMessage.trim()) return;
            
            const content = this.newMessage;
            this.newMessage = '';
            
            try {
                const response = await fetch(`/social/messages/${this.conversationId}/send`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content })
                });
                
                const data = await response.json();
                
                if (data.message) {
                    this.messages.push({
                        id: data.message.id,
                        user_id: data.message.user_id,
                        content: data.message.content,
                        created_at: data.message.created_at,
                    });
                    
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                }
            } catch (error) {
                console.error('Error sending message:', error);
                alert('Failed to send message. Please try again.');
            }
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
        }
    }
}
</script>
@endpush
