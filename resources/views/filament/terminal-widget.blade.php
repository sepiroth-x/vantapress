{{-- Floating Terminal Widget - Standalone --}}
<div x-data="{
    isOpen: false,
    command: '',
    history: [],
    username: '{{ auth()->user()->name ?? 'admin' }}',
    prompt: '{{ (auth()->user()->name ?? 'admin') . '@vantapress:~$ ' }}',
    isDragging: false,
    dragStartX: 0,
    dragStartY: 0,
    terminalX: null,
    terminalY: null,
    isButtonDragging: false,
    buttonX: null,
    buttonY: null,
    buttonDragStartX: 0,
    buttonDragStartY: 0,
    isResizing: false,
    resizeStartX: 0,
    resizeStartY: 0,
    terminalWidth: 1024,
    terminalHeight: 1024,
    
    init() {
        this.history.push({
            type: 'output',
            content: '<div style="border: 1px solid #00ff41; padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;">' +
                     '<div style="text-align: center; font-size: 1.25rem; font-weight: bold; margin-bottom: 0.5rem; color: #39ff14;">⚡ THE VILLAIN TERMINAL ⚡</div>' +
                     '<div style="text-align: center; font-size: 0.875rem; margin-bottom: 0.75rem; color: #00ff41;">VantaPress Terminal v1.0.0</div>' +
                     '<div style="text-align: center; font-size: 0.75rem; color: #00ff41; opacity: 0.8;">Type <span style="color: #39ff14; font-weight: bold;">vanta-help</span> for available commands</div>' +
                     '<div style="border-top: 1px solid #00ff41; margin-top: 0.75rem; padding-top: 0.75rem; text-align: center; font-size: 0.75rem; color: #00ff41; opacity: 0.7; font-style: italic;">Proudly created by Sepiroth X Villainous</div>' +
                     '</div>'
        });
        
        document.addEventListener('mousemove', (e) => {
            this.drag(e);
            this.dragButton(e);
            this.resize(e);
        });
        document.addEventListener('mouseup', () => {
            this.stopDrag();
            this.stopButtonDrag();
            this.stopResize();
        });
    },
    
    toggle() {
        this.isOpen = !this.isOpen;
        if (this.isOpen) {
            // Position terminal window above the button
            const buttonRect = this.$refs.terminalButton.getBoundingClientRect();
            // If button has custom position, position terminal above it
            if (this.buttonX !== null) {
                this.terminalX = Math.max(10, Math.min(this.buttonX, window.innerWidth - this.terminalWidth - 10));
                this.terminalY = Math.max(10, buttonRect.top - this.terminalHeight - 10);
            }
            this.$nextTick(() => {
                this.$refs.commandInput?.focus();
            });
        }
    },
    
    startButtonDrag(event) {
        const startX = event.clientX;
        const startY = event.clientY;
        const rect = this.$refs.terminalButton.getBoundingClientRect();
        this.buttonDragStartX = event.clientX - rect.left;
        this.buttonDragStartY = event.clientY - rect.top;
        
        let hasMoved = false;
        
        const moveHandler = (e) => {
            const deltaX = Math.abs(e.clientX - startX);
            const deltaY = Math.abs(e.clientY - startY);
            if (deltaX > 3 || deltaY > 3) {
                hasMoved = true;
                this.isButtonDragging = true;
            }
        };
        
        const upHandler = () => {
            document.removeEventListener('mousemove', moveHandler);
            document.removeEventListener('mouseup', upHandler);
            if (!hasMoved) {
                this.toggle();
            }
            setTimeout(() => { this.isButtonDragging = false; }, 50);
        };
        
        document.addEventListener('mousemove', moveHandler);
        document.addEventListener('mouseup', upHandler);
    },
    
    dragButton(event) {
        if (!this.isButtonDragging) return;
        this.buttonX = event.clientX - this.buttonDragStartX;
        this.buttonY = event.clientY - this.buttonDragStartY;
    },
    
    stopButtonDrag() {
        this.isButtonDragging = false;
    },
    
    startDrag(event) {
        console.log('startDrag called');
        this.isDragging = true;
        const rect = this.$refs.terminalWindow.getBoundingClientRect();
        this.dragStartX = event.clientX - rect.left;
        this.dragStartY = event.clientY - rect.top;
        console.log('Drag started:', { x: this.dragStartX, y: this.dragStartY });
    },
    
    drag(event) {
        if (!this.isDragging) return;
        this.terminalX = event.clientX - this.dragStartX;
        this.terminalY = event.clientY - this.dragStartY;
        console.log('Dragging:', { x: this.terminalX, y: this.terminalY });
    },
    
    stopDrag() {
        this.isDragging = false;
    },
    
    startResize(event) {
        this.isResizing = true;
        this.resizeStartX = event.clientX;
        this.resizeStartY = event.clientY;
        const rect = this.$refs.terminalWindow.getBoundingClientRect();
        this.terminalWidth = rect.width;
        this.terminalHeight = rect.height;
    },
    
    resize(event) {
        if (!this.isResizing) return;
        const deltaX = event.clientX - this.resizeStartX;
        const deltaY = event.clientY - this.resizeStartY;
        const maxHeight = window.innerHeight - 200;
        this.terminalWidth = Math.max(600, Math.min(1920, this.terminalWidth + deltaX));
        this.terminalHeight = Math.max(300, Math.min(maxHeight, this.terminalHeight + deltaY));
        this.resizeStartX = event.clientX;
        this.resizeStartY = event.clientY;
    },
    
    stopResize() {
        this.isResizing = false;
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
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response. Check if route is configured correctly.');
            }
            
            const data = await response.json();
            
            this.history.push({
                type: data.success ? 'output' : 'error',
                content: data.output.replace(/\n/g, '<br>')
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
}">
    <!-- Floating Terminal Button -->
    <div 
        x-ref="terminalButton"
        :style="buttonX !== null ? 
            `position: fixed !important; left: ${buttonX}px !important; top: ${buttonY}px !important; z-index: 999999 !important;` : 
            'position: fixed !important; left: 280px !important; bottom: 24px !important; z-index: 999999 !important;'"
    >
        <button 
            @mousedown="startButtonDrag($event)"
            @mouseenter="$el.style.backgroundColor = '#1f2937'"
            @mouseleave="$el.style.backgroundColor = '#111827'"
            :class="{ 'ring-2 ring-green-500 dark:ring-green-400': isOpen }"
            :style="(isButtonDragging ? 'cursor: grabbing;' : 'cursor: grab;') + ' display: flex !important; align-items: center !important; gap: 0.5rem !important; padding: 0.5rem 1rem !important; border-radius: 0.5rem !important; font-weight: 500 !important; background-color: #111827 !important; color: white !important; border: 1px solid #374151 !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important; transition: background-color 0.2s !important; pointer-events: auto !important;'"
            title="Click to toggle, Drag to move"
            type="button"
        >
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span style="font-size: 0.875rem;">Terminal</span>
            <svg x-show="isOpen" style="width: 1rem; height: 1rem;" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
            <svg x-show="!isOpen" style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    <!-- Floating Terminal Window -->
    <div 
        x-ref="terminalWindow"
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 transform translate-y-4 scale-95"
        :style="(terminalX !== null ? 
            `position: fixed !important; left: ${terminalX}px !important; top: ${terminalY}px !important;` : 
            'position: fixed !important; bottom: 160px !important; right: 24px !important;') + 
            ` z-index: 999998 !important; width: ${terminalWidth}px !important; height: ${terminalHeight}px !important; max-width: ${terminalWidth}px !important; max-height: calc(100vh - 200px) !important; min-width: 600px !important; min-height: 300px !important; background: #000000 !important; border: 2px solid #00ff41 !important; border-radius: 0.75rem !important; box-shadow: 0 0 40px rgba(0, 255, 65, 0.3), 0 25px 50px -12px rgba(0, 0, 0, 0.8) !important; display: flex !important; flex-direction: column !important; overflow: hidden !important;`"
    >
        <!-- Terminal Header (Draggable) -->
        <div 
            @mousedown="startDrag($event)"
            :style="'cursor: ' + (isDragging ? 'grabbing' : 'grab') + '; user-select: none;'"
            style="display: flex !important; align-items: center !important; justify-content: center !important; padding: 0.75rem 1rem !important; background: linear-gradient(180deg, #001a0d 0%, #000000 100%) !important; border-bottom: 2px solid #00ff41 !important; border-radius: 0.75rem 0.75rem 0 0 !important; box-shadow: 0 2px 10px rgba(0, 255, 65, 0.2) !important;"
        >
            <!-- Terminal Title (Centered) -->
            <h3 style="font-size: 0.875rem; font-weight: 700; background: linear-gradient(90deg, #00ff41, #39ff14); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 0.05em; pointer-events: none; text-align: center !important; margin: 0 auto !important;">⚡ THE VILLAIN TERMINAL ⚡</h3>
        </div>

        <!-- Terminal Output and Input -->
        <div 
            x-ref="terminalOutput"
            style="flex: 1 !important; overflow-y: auto !important; padding: 1rem !important; font-family: 'Courier New', 'Consolas', monospace !important; font-size: 0.875rem !important; color: #00ff41 !important; background: linear-gradient(180deg, #001a0d 0%, #000000 100%) !important; scrollbar-width: thin; scrollbar-color: #00ff41 #001a0d;"
        >
            <template x-for="(entry, index) in history" :key="index">
                <div>
                    <div x-show="entry.type === 'command'" style="color: #39ff14; margin-bottom: 0.25rem; font-weight: 600;" x-html="entry.content"></div>
                    <div x-show="entry.type === 'error'" style="color: #ff4141; margin-bottom: 0.5rem; font-weight: 600;" x-html="entry.content"></div>
                    <div x-show="entry.type === 'output'" style="color: #00ff41; margin-bottom: 0.5rem; line-height: 1.5; opacity: 0.9;" x-html="entry.content"></div>
                </div>
            </template>
            
            <!-- Terminal Input (inside scrollable area) -->
            <div style="margin-top: 0.5rem;">
                <form @submit.prevent="executeCommand" style="display: flex !important; align-items: center !important; gap: 0.5rem !important;">
                    <span style="color: #00ff41; font-family: 'Courier New', monospace; font-size: 0.875rem; white-space: nowrap; flex-shrink: 0; font-weight: 700;" x-text="prompt"></span>
                    <input 
                        type="text" 
                        x-model="command"
                        @keydown.enter.prevent="executeCommand"
                        style="flex: 1 !important; background: transparent !important; border: none !important; border-radius: 0 !important; outline: none !important; color: #00ff41 !important; font-family: 'Courier New', monospace !important; font-size: 0.875rem !important; padding: 0 !important; min-width: 0 !important; caret-color: #00ff41 !important; box-shadow: none !important;"
                        placeholder=""
                        x-ref="commandInput"
                        autocomplete="off"
                        spellcheck="false"
                    />
                </form>
            </div>
        </div>
        
        <!-- Resize Handle -->
        <div 
            @mousedown.stop="startResize($event)"
            style="position: absolute !important; bottom: 0 !important; right: 0 !important; width: 24px !important; height: 24px !important; cursor: nwse-resize !important; z-index: 10 !important;"
            title="Drag to resize"
        >
            <svg style="width: 100%; height: 100%; color: #00ff41; opacity: 0.6; filter: drop-shadow(0 0 4px rgba(0, 255, 65, 0.5));" fill="currentColor" viewBox="0 0 20 20">
                <path d="M20 20L20 15L15 20L20 20Z M20 10L10 20L15 20L20 15L20 10Z M20 5L5 20L10 20L20 10L20 5Z"/>
            </svg>
        </div>
    </div>
</div>
