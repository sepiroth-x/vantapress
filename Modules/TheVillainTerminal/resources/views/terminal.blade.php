<x-filament-panels::page>
    <div class="villain-terminal-container">
        <div class="terminal-header">
            <div class="terminal-controls">
                <span class="terminal-control close"></span>
                <span class="terminal-control minimize"></span>
                <span class="terminal-control maximize"></span>
            </div>
            <div class="terminal-title">{{ $this->username }}@villain-terminal: ~</div>
        </div>

        <div class="terminal-body" id="terminal-output">
            <div class="terminal-line">
                <span class="terminal-welcome">
╔════════════════════════════════════════════════════╗
║         THE VILLAIN TERMINAL v1.0                  ║
║     Web-Based Terminal for VantaPress              ║
╚════════════════════════════════════════════════════╝
                </span>
            </div>
            <div class="terminal-line">
                <span style="color: #00ff00;">Welcome, {{ $this->username }}!</span>
            </div>
            <div class="terminal-line">
                <span style="color: #ffff00;">Type 'vanta-help' to see available commands.</span>
            </div>
            <div class="terminal-line">&nbsp;</div>
        </div>

        <div class="terminal-input-container">
            <span class="terminal-prompt">{{ $this->prompt }}</span>
            <input 
                type="text" 
                id="terminal-input" 
                class="terminal-input" 
                autofocus 
                autocomplete="off"
                spellcheck="false"
            />
        </div>
    </div>

    <style>
        .villain-terminal-container {
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            font-family: 'Courier New', monospace;
            margin: 20px 0;
        }

        .terminal-header {
            background: #2d2d2d;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #3d3d3d;
        }

        .terminal-controls {
            display: flex;
            gap: 8px;
            margin-right: 15px;
        }

        .terminal-control {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .terminal-control.close {
            background: #ff5f56;
        }

        .terminal-control.minimize {
            background: #ffbd2e;
        }

        .terminal-control.maximize {
            background: #27c93f;
        }

        .terminal-title {
            color: #b4b4b4;
            font-size: 13px;
        }

        .terminal-body {
            background: #000;
            color: #00ff00;
            padding: 20px;
            min-height: 500px;
            max-height: 600px;
            overflow-y: auto;
            font-size: 14px;
            line-height: 1.6;
        }

        .terminal-body::-webkit-scrollbar {
            width: 10px;
        }

        .terminal-body::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        .terminal-body::-webkit-scrollbar-thumb {
            background: #444;
            border-radius: 5px;
        }

        .terminal-body::-webkit-scrollbar-thumb:hover {
            background: #666;
        }

        .terminal-line {
            margin-bottom: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .terminal-welcome {
            color: #00ffff;
            font-weight: bold;
        }

        .terminal-input-container {
            background: #000;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            border-top: 1px solid #3d3d3d;
        }

        .terminal-prompt {
            color: #00ff00;
            margin-right: 8px;
            font-weight: bold;
            white-space: nowrap;
        }

        .terminal-input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            caret-color: #00ff00;
        }

        .terminal-input::selection {
            background: #00ff0050;
        }

        .terminal-command {
            color: #00ff00;
        }

        .terminal-output {
            color: #b4b4b4;
        }

        .terminal-error {
            color: #ff5f56;
        }

        .terminal-success {
            color: #27c93f;
        }

        .terminal-warning {
            color: #ffbd2e;
        }

        /* Loading cursor animation */
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0; }
        }

        .terminal-cursor {
            animation: blink 1s infinite;
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('terminal-input');
            const output = document.getElementById('terminal-output');
            const prompt = '{{ $this->prompt }}';
            
            let commandHistory = [];
            let historyIndex = -1;
            let availableCommands = [];

            // Keep input focused
            output.addEventListener('click', () => input.focus());
            
            // Load available commands for autocomplete
            @this.call('getAvailableCommands').then(commands => {
                availableCommands = commands;
            });

            // Handle command input
            input.addEventListener('keydown', async function(e) {
                // Enter key - execute command
                if (e.key === 'Enter') {
                    const command = input.value.trim();
                    
                    if (command) {
                        // Add to history
                        commandHistory.unshift(command);
                        historyIndex = -1;
                        
                        // Display command
                        addLine(`<span class="terminal-prompt">${prompt}</span><span class="terminal-command">${escapeHtml(command)}</span>`);
                        
                        // Clear input
                        input.value = '';
                        
                        // Execute command
                        try {
                            const result = await @this.call('executeCommand', command);
                            
                            if (result.clear) {
                                // Clear terminal
                                output.innerHTML = '';
                            } else if (result.output) {
                                // Display output
                                addLine(result.output);
                            }
                            
                            if (!result.success && result.error) {
                                // Error styling already in output
                            }
                            
                        } catch (error) {
                            addLine(`<span style="color: #ff5f56;">Error: ${error.message}</span>`);
                        }
                    }
                    
                    scrollToBottom();
                }
                
                // Up arrow - previous command
                else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (historyIndex < commandHistory.length - 1) {
                        historyIndex++;
                        input.value = commandHistory[historyIndex];
                    }
                }
                
                // Down arrow - next command
                else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (historyIndex > 0) {
                        historyIndex--;
                        input.value = commandHistory[historyIndex];
                    } else if (historyIndex === 0) {
                        historyIndex = -1;
                        input.value = '';
                    }
                }
                
                // Tab key - autocomplete
                else if (e.key === 'Tab') {
                    e.preventDefault();
                    const partial = input.value.toLowerCase();
                    
                    if (partial) {
                        const matches = availableCommands.filter(cmd => 
                            cmd.toLowerCase().startsWith(partial)
                        );
                        
                        if (matches.length === 1) {
                            input.value = matches[0];
                        } else if (matches.length > 1) {
                            addLine(`<span class="terminal-prompt">${prompt}</span>${escapeHtml(partial)}`);
                            addLine(`<span style="color: #ffff00;">Suggestions:</span>\n  ${matches.join('\n  ')}`);
                            scrollToBottom();
                        }
                    }
                }
            });

            // Helper: Add line to output
            function addLine(content) {
                const line = document.createElement('div');
                line.className = 'terminal-line';
                line.innerHTML = content;
                output.appendChild(line);
            }

            // Helper: Scroll to bottom
            function scrollToBottom() {
                output.scrollTop = output.scrollHeight;
            }

            // Helper: Escape HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Focus input on load
            input.focus();
        });
    </script>
    @endpush
</x-filament-panels::page>
