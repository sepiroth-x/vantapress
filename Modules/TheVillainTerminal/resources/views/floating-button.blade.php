{{-- Floating Terminal Button Widget --}}
@php
    $username = $username ?? (auth()->user()->name ?? 'admin');
    $prompt = $prompt ?? ($username . '@vantapress:~$ ');
@endphp

<div x-data="{
    isOpen: false,
    command: '',
    history: [],
    username: '{{ $username }}',
    prompt: '{{ $prompt }}',
    
    init() {
        this.history.push({
            type: 'output',
            content: 'VantaPress Terminal v1.0.0\\nType \\'vanta-help\\' for available commands.\\n'
        });
    },
    
    toggle() {
        this.isOpen = !this.isOpen;
    },
    
    async executeCommand() {
        if (!this.command.trim()) return;
        
        this.history.push({
            type: 'command',
            content: this.prompt + this.command
        });
        
        if (this.command === 'clear') {
            this.history = [];
            this.command = '';
            return;
        }
        
        try {
            const response = await fetch('/admin/terminal/execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({ command: this.command })
            });
            
            const data = await response.json();
            
            this.history.push({
                type: data.success ? 'output' : 'error',
                content: data.output
            });
        } catch (error) {
            this.history.push({
                type: 'error',
                content: 'Error executing command: ' + error.message
            });
        }
        
        this.command = '';
        this.$nextTick(() => {
            this.$refs.terminalOutput.scrollTop = this.$refs.terminalOutput.scrollHeight;
        });
    }
}" style="pointer-events: none;">
    {{-- Floating Terminal Button in Navigation Bar --}}
    <div style="position: fixed !important; bottom: 2rem !important; right: 2rem !important; z-index: 99999 !important; pointer-events: auto;">
        <button 
            @click="toggle"
            :class="{
                'ring-2 ring-gray-600 dark:ring-gray-500': isOpen
            }"
            class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200 bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700 border border-gray-700 dark:border-gray-600 shadow-lg hover:shadow-xl"
            style="display: flex !important; visibility: visible !important;"
            title="Toggle Terminal"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="text-sm">Terminal</span>
            <svg x-show="isOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="!isOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    {{-- Floating Terminal Window (Chatbox Style) --}}
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-4"
        class="fixed top-20 right-4 z-40 w-[600px] h-[500px] bg-gray-900 dark:bg-gray-950 rounded-lg shadow-2xl border border-gray-700 dark:border-gray-600 flex flex-col"
        style="display: none;"
    >
        {{-- Terminal Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-gray-800 dark:bg-gray-900 border-b border-gray-700 dark:border-gray-600 rounded-t-lg">
            <div class="flex items-center gap-2">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                </div>
                <span class="ml-2 text-sm font-medium text-gray-300">VantaPress Terminal</span>
            </div>
            <button @click="toggle" class="text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Terminal Output --}}
        <div 
            x-ref="terminalOutput"
            class="flex-1 overflow-y-auto p-4 font-mono text-sm text-green-400 bg-gray-900 dark:bg-gray-950"
        >
            <template x-for="(item, index) in history" :key="index">
                <div>
                    <span 
                        x-text="item.content"
                        :class="{
                            'text-green-400': item.type === 'output',
                            'text-red-400': item.type === 'error',
                            'text-blue-400': item.type === 'command'
                        }"
                        class="whitespace-pre-wrap break-words"
                    ></span>
                </div>
            </template>
        </div>

        {{-- Terminal Input --}}
        <div class="p-4 bg-gray-800 dark:bg-gray-900 border-t border-gray-700 dark:border-gray-600 rounded-b-lg">
            <form @submit.prevent="executeCommand" class="flex items-center gap-2">
                <span class="text-green-400 font-mono text-sm" x-text="prompt"></span>
                <input 
                    type="text"
                    x-model="command"
                    class="flex-1 bg-transparent border-none focus:ring-0 text-green-400 font-mono text-sm placeholder-gray-600"
                    placeholder="Type a command..."
                    autofocus
                />
            </form>
        </div>
    </div>
</div>
