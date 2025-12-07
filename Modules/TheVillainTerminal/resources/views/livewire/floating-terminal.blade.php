<div 
x-data="{
    isOpen: false,
    command: '',
    history: [],
    username: '{{ $username ?? (auth()->user()->name ?? 'admin') }}',
    prompt: '{{ $prompt ?? ((auth()->user()->name ?? 'admin') . '@vantapress:~$ ') }}',
    buttonX: 290,
    buttonY: null,
    isDragging: false,
    dragStartX: 0,
    dragStartY: 0,
    clickX: 0,
    clickY: 0,
    
    init() {
        this.history.push({
            type: 'output',
            content: 'VantaPress Terminal v1.0.0\\nType \\'vanta-help\\' for available commands.\\n'
        });
        
        // Set initial Y position
        this.buttonY = window.innerHeight - 100;
        console.log('Terminal button initialized at:', this.buttonX, this.buttonY);
    },
    
    toggle() {
        console.log('Toggle called, isOpen:', this.isOpen);
        this.isOpen = !this.isOpen;
    },
    
    startDrag(e) {
        if (e.button !== 0) return;
        
        this.clickX = e.clientX;
        this.clickY = e.clientY;
        this.dragStartX = e.clientX - this.buttonX;
        this.dragStartY = e.clientY - this.buttonY;
        
        console.log('Drag started at:', e.clientX, e.clientY);
        console.log('Button position:', this.buttonX, this.buttonY);
        
        // Delay setting isDragging to allow click detection
        setTimeout(() => {
            if (Math.abs(e.clientX - this.clickX) > 5 || Math.abs(e.clientY - this.clickY) > 5) {
                this.isDragging = true;
            }
        }, 50);
        
        e.preventDefault();
    },
    
    drag(e) {
        if (!this.isDragging) return;
        
        this.buttonX = e.clientX - this.dragStartX;
        this.buttonY = e.clientY - this.dragStartY;
        
        console.log('Dragging to:', this.buttonX, this.buttonY);
    },
    
    stopDrag(e) {
        console.log('Mouse up - isDragging:', this.isDragging);
        
        // If we didn't drag, it's a click
        if (!this.isDragging) {
            const distX = Math.abs(e.clientX - this.clickX);
            const distY = Math.abs(e.clientY - this.clickY);
            console.log('Distance moved:', distX, distY);
            
            if (distX < 5 && distY < 5) {
                console.log('Detected as click, toggling...');
                this.toggle();
            }
        }
        
        this.isDragging = false;
    },
    
    getButtonStyle() {
        return `left: ${this.buttonX}px; top: ${this.buttonY}px;`;
    },
    
    getWindowStyle() {
        // Position terminal window above the button
        const windowX = Math.max(0, this.buttonX - 512 + 75);
        const windowY = Math.max(0, this.buttonY - 1024 - 10);
        return `left: ${windowX}px; top: ${windowY}px;`;
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
}" 
@mousemove.window="drag($event)"
@mouseup.window="stopDrag($event)">
    
    <!-- Floating Terminal Button (Draggable) -->
    <div 
        @mousedown="startDrag($event)"
        :style="'position: fixed; left: ' + buttonX + 'px; top: ' + buttonY + 'px; z-index: 99999; pointer-events: auto; cursor: move;'"
    >
        <button 
            :class="{
                'ring-2 ring-gray-600 dark:ring-gray-500': isOpen
            }"
            style="
                display: flex !important; 
                visibility: visible !important; 
                background-color: #111827 !important;
                color: white !important;
                padding: 0.5rem 1rem !important;
                border-radius: 0.5rem !important;
                border: 1px solid #374151 !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
                align-items: center !important;
                gap: 0.5rem !important;
                cursor: move !important;
                user-select: none;
            "
            class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-all duration-200 bg-gray-900 dark:bg-gray-800 text-white hover:bg-gray-800 dark:hover:bg-gray-700 border border-gray-700 dark:border-gray-600 shadow-lg hover:shadow-xl"
            title="Toggle Terminal (Drag to move)"
        >
            <svg style="width: 1.25rem; height: 1.25rem; color: white;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span style="font-size: 0.875rem; color: white !important;" class="text-sm">Terminal</span>
            <svg x-show="isOpen" style="width: 1rem; height: 1rem; color: white;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="!isOpen" style="width: 1rem; height: 1rem; color: white;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    <!-- Floating Terminal Window (1024x1024) -->
    <div 
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        :style="getWindowStyle()"
        class="fixed z-40 bg-gray-900 dark:bg-gray-950 rounded-lg shadow-2xl border border-gray-700 dark:border-gray-600 flex flex-col"
        style="
            display: none; 
            pointer-events: auto;
            position: fixed !important;
            width: 1024px !important;
            height: 1024px !important;
            background-color: #111827 !important;
            z-index: 99998 !important;
        "
    >
        <!-- Terminal Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-gray-800 dark:bg-gray-900 border-b border-gray-700 dark:border-gray-600 rounded-t-lg">
            <div class="flex items-center gap-2">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                </div>
                <h3 class="text-sm font-semibold text-white ml-2">The Villain Terminal</h3>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400" x-text="username + '@vantapress'"></span>
            </div>
        </div>

        <!-- Terminal Output -->
        <div 
            x-ref="terminalOutput"
            class="flex-1 overflow-y-auto p-4 font-mono text-sm text-gray-100 bg-gray-900 dark:bg-gray-950 scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900"
        >
            <template x-for="(entry, index) in history" :key="index">
                <div>
                    <div x-show="entry.type === 'command'" class="text-green-400 mb-1" x-text="entry.content"></div>
                    <div x-show="entry.type === 'error'" class="text-red-400 mb-2 whitespace-pre-wrap" x-text="entry.content"></div>
                    <div x-show="entry.type === 'output'" class="text-gray-300 mb-2 whitespace-pre-wrap" x-text="entry.content"></div>
                </div>
            </template>
        </div>

        <!-- Terminal Input -->
        <div class="px-4 py-3 bg-gray-800 dark:bg-gray-900 border-t border-gray-700 dark:border-gray-600 rounded-b-lg">
            <form @submit.prevent="executeCommand" class="flex items-center gap-2">
                <span class="text-green-400 font-mono text-sm whitespace-nowrap" x-text="prompt"></span>
                <input 
                    type="text" 
                    x-model="command"
                    class="flex-1 bg-transparent border-none focus:ring-0 text-gray-100 font-mono text-sm p-0"
                    placeholder="Type a command..."
                    autofocus
                />
                <button 
                    type="submit"
                    class="px-3 py-1 bg-green-600 hover:bg-green-500 text-white rounded text-xs font-medium transition-colors"
                >
                    Run
                </button>
            </form>
            <div class="mt-2 text-xs text-gray-500">
                Press Enter to execute • Type 'vanta-help' for commands • 'clear' to clear screen
            </div>
        </div>
    </div>
</div>
