<div 
x-data="{
    isOpen: false,
    showButton: true,
    command: '',
    history: [],
    username: @js($username ?? (auth()->user()->name ?? 'admin')),
    prompt: @js($prompt ?? ((auth()->user()->name ?? 'admin') . '@vantapress:~$ ')),
    buttonX: 290,
    buttonY: null,
    isDragging: false,
    isMouseDown: false,
    dragStartX: 0,
    dragStartY: 0,
    clickX: 0,
    clickY: 0,
    
    // Terminal window position and size
    terminalX: null,
    terminalY: null,
    terminalWidth: 800,
    terminalHeight: 600,
    isDraggingTerminal: false,
    terminalDragStartX: 0,
    terminalDragStartY: 0,
    
    init() {
        this.history.push({
            type: 'output',
            content: 'VantaPress Terminal v1.0.0\nType ' + String.fromCharCode(39) + 'vanta-help' + String.fromCharCode(39) + ' for available commands.\n'
        });
        
        // Set initial Y position for button
        this.buttonY = window.innerHeight - 100;
        
        // Set initial terminal position (centered)
        this.terminalX = (window.innerWidth - this.terminalWidth) / 2;
        this.terminalY = (window.innerHeight - this.terminalHeight) / 2;
        
        console.log('Terminal button initialized at:', this.buttonX, this.buttonY);
    },
    
    toggle() {
        console.log('Toggle called, isOpen:', this.isOpen);
        this.isOpen = !this.isOpen;
        
        // Position terminal relative to button when opening
        if (this.isOpen) {
            // Hide the button when terminal opens
            this.showButton = false;
            
            // Position terminal above and to the right of the button
            this.terminalX = this.buttonX + 60;
            this.terminalY = Math.max(20, this.buttonY - this.terminalHeight - 20);
            
            // Make sure terminal doesn't go off screen
            if (this.terminalX + this.terminalWidth > window.innerWidth) {
                this.terminalX = window.innerWidth - this.terminalWidth - 20;
            }
            if (this.terminalY < 20) {
                this.terminalY = 20;
            }
        }
    },
    
    closeTerminal() {
        this.isOpen = false;
        this.showButton = true;
    },
    
    startDrag(e) {
        if (e.button !== 0) return;
        
        this.isMouseDown = true;
        this.clickX = e.clientX;
        this.clickY = e.clientY;
        this.dragStartX = e.clientX - this.buttonX;
        this.dragStartY = e.clientY - this.buttonY;
        
        console.log('Mouse down at:', e.clientX, e.clientY);
        
        e.preventDefault();
        e.stopPropagation();
    },
    
    drag(e) {
        if (!this.isMouseDown) return;
        
        // Check if mouse has moved enough to start dragging
        const distX = Math.abs(e.clientX - this.clickX);
        const distY = Math.abs(e.clientY - this.clickY);
        
        if (!this.isDragging && (distX > 5 || distY > 5)) {
            this.isDragging = true;
            console.log('Started dragging');
        }
        
        if (this.isDragging) {
            this.buttonX = e.clientX - this.dragStartX;
            this.buttonY = e.clientY - this.dragStartY;
        }
    },
    
    stopDrag(e) {
        if (!this.isMouseDown) return;
        
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
        
        this.isMouseDown = false;
        this.isDragging = false;
    },
    
    startTerminalDrag(e) {
        if (e.button !== 0) return;
        
        this.isDraggingTerminal = true;
        this.terminalDragStartX = e.clientX - this.terminalX;
        this.terminalDragStartY = e.clientY - this.terminalY;
        
        e.preventDefault();
        e.stopPropagation();
    },
    
    dragTerminal(e) {
        if (!this.isDraggingTerminal) return;
        
        this.terminalX = e.clientX - this.terminalDragStartX;
        this.terminalY = e.clientY - this.terminalDragStartY;
    },
    
    stopTerminalDrag() {
        this.isDraggingTerminal = false;
    },
    
    getButtonStyle() {
        return `left: ${this.buttonX}px; top: ${this.buttonY}px;`;
    },
    
    getTerminalStyle() {
        const display = this.isOpen ? 'flex' : 'none';
        return `display: ${display}; position: fixed; z-index: 99998; pointer-events: auto; left: ${this.terminalX}px; top: ${this.terminalY}px; width: ${this.terminalWidth}px; height: ${this.terminalHeight}px;`;
    },
    
    async executeCommand() {
        if (!this.command.trim()) return;
        
        this.history.push({
            type: 'command',
            content: this.prompt + this.command
        });
        
        if (this.command === 'clear') {
            this.history = [];
            this.history.push({
                type: 'output',
                content: 'VantaPress Terminal v1.0.0\nType ' + String.fromCharCode(39) + 'vanta-help' + String.fromCharCode(39) + ' for available commands.\n'
            });
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
@mousemove.window="isMouseDown || isDraggingTerminal ? (isMouseDown ? drag($event) : dragTerminal($event)) : null"
@mouseup.window="stopDrag($event); stopTerminalDrag()">
    
    <!-- Floating Terminal Button (Draggable) -->
    <div 
        x-show="showButton"
        @mousedown="startDrag($event)"
        style="position: fixed; z-index: 99999; pointer-events: auto;"
        :style="{ left: buttonX + 'px', top: buttonY + 'px' }"
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

    <!-- Floating Terminal Window -->
    <div 
        :style="getTerminalStyle()"
        class="flex-col rounded-lg shadow-2xl overflow-hidden border border-gray-700"
        style="background-color: #0a0a0a;"
        @click.stop
    >
        <!-- Terminal Header (Draggable) -->
        <div 
            @mousedown="startTerminalDrag($event)"
            class="flex items-center justify-between px-4 py-2 cursor-move select-none"
            style="background: linear-gradient(to bottom, #1a1a1a, #0f0f0f); border-bottom: 1px solid #2a2a2a;"
        >
            <h3 class="font-semibold font-mono flex-1 text-center pointer-events-none" style="color: #00ff41; font-size: 11px; white-space: nowrap;">THE VILLAIN TERMINAL V.1.0.0</h3>
            <button @click.stop="closeTerminal()" class="p-1 rounded transition-colors hover:bg-gray-800" style="color: #00ff41;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Terminal Output -->
        <div 
            x-ref="terminalOutput"
            class="flex-1 overflow-y-auto p-4 font-mono text-sm"
            style="background-color: #000000; min-height: 400px; max-height: 500px; color: #00ff41;"
        >
            <template x-for="(entry, index) in history" :key="index">
                <div>
                    <div x-show="entry.type === 'command'" class="mb-1 font-semibold" style="color: #00ff41;" x-text="entry.content"></div>
                    <div x-show="entry.type === 'error'" class="mb-2" style="color: #ff4444; white-space: pre-line;" x-html="entry.content"></div>
                    <div x-show="entry.type === 'output'" class="mb-2" style="color: #00ff41; white-space: pre-line;" x-html="entry.content"></div>
                </div>
            </template>
        </div>

        <!-- Terminal Input -->
        <div 
            class="px-4 py-3"
            style="background: linear-gradient(to top, #0a0a0a, #000000); border-top: 1px solid #1a4d1a;"
        >
            <form @submit.prevent="executeCommand" class="flex items-center gap-2">
                <span class="font-mono text-sm font-semibold whitespace-nowrap" style="color: #00ff41;" x-text="prompt"></span>
                <input 
                    type="text" 
                    x-model="command"
                    class="flex-1 bg-transparent border-none focus:ring-0 font-mono text-sm p-0 outline-none"
                    style="background-color: transparent !important; color: #00ff41 !important; caret-color: #00ff41; placeholder-color: #004400;"
                    placeholder="Type a command..."
                    autofocus
                />
            </form>
        </div>
    </div>
</div>
