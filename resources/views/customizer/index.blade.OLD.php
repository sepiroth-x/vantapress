<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Customize: {{ $theme->name }} - VantaPress</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            overflow: hidden;
            height: 100vh;
        }
        
        .customizer-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }
        
        /* Sidebar Controls */
        .customizer-sidebar {
            width: 380px;
            background: #fff;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .customizer-header {
            padding: 16px 20px;
            background: #1e293b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        
        .customizer-header h1 {
            font-size: 18px;
            font-weight: 600;
        }
        
        .close-customizer {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: background 0.2s;
        }
        
        .close-customizer:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .theme-info {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            flex-shrink: 0;
        }
        
        .theme-info h2 {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .theme-info p {
            font-size: 13px;
            color: #64748b;
        }
        
        .theme-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: #10b981;
            color: white;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .customizer-controls {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        
        .customizer-footer {
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            gap: 12px;
            flex-shrink: 0;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: white;
            flex: 1;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-success {
            background: #10b981;
            color: white;
            flex: 1;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-secondary {
            background: #e5e7eb;
            color: #1e293b;
        }
        
        .btn-secondary:hover {
            background: #d1d5db;
        }
        
        /* Form Controls */
        .control-section {
            margin-bottom: 24px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }
        
        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-textarea-code {
            font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
            font-size: 12px;
        }
        
        .form-color {
            width: 100%;
            height: 42px;
            padding: 4px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .form-color::-webkit-color-swatch-wrapper {
            padding: 0;
        }
        
        .form-color::-webkit-color-swatch {
            border: none;
            border-radius: 4px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        /* Preview Panel */
        .customizer-preview {
            flex: 1;
            background: #f3f4f6;
            display: flex;
            flex-direction: column;
        }
        
        .preview-header {
            padding: 12px 20px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        
        .preview-header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .preview-header-title {
            font-weight: 500;
            font-size: 14px;
        }
        
        .preview-url {
            padding: 6px 12px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 13px;
            color: #64748b;
            min-width: 300px;
        }
        
        .preview-actions {
            display: flex;
            gap: 8px;
        }
        
        .device-toggle {
            display: flex;
            gap: 4px;
            background: #f3f4f6;
            padding: 4px;
            border-radius: 6px;
        }
        
        .device-btn {
            padding: 6px 10px;
            background: transparent;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: #64748b;
            transition: all 0.2s;
        }
        
        .device-btn:hover {
            color: #1e293b;
            background: rgba(59, 130, 246, 0.1);
        }
        
        .device-btn.active {
            background: white;
            color: #3b82f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .icon-btn {
            padding: 8px;
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            cursor: pointer;
            color: #64748b;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }
        
        .icon-btn:hover {
            background: #f3f4f6;
            color: #1e293b;
        }
        
        .preview-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }
        
        .preview-frame-wrapper {
            width: 100%;
            height: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .preview-frame-wrapper.tablet-view {
            max-width: 768px;
            height: 90%;
        }
        
        .preview-frame-wrapper.mobile-view {
            max-width: 375px;
            height: 95%;
        }
        
        #preview-frame {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        /* Accordion */
        .accordion {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        
        .accordion-header {
            padding: 12px 16px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 500;
            font-size: 14px;
            transition: background 0.2s;
        }
        
        .accordion-header:hover {
            background: #f3f4f6;
        }
        
        .accordion-icon {
            transition: transform 0.2s;
        }
        
        .accordion.active .accordion-icon {
            transform: rotate(180deg);
        }
        
        .accordion-content {
            padding: 16px;
            display: none;
        }
        
        .accordion.active .accordion-content {
            display: block;
        }
        
        /* Loading State */
        .saving-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: #1e293b;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            display: none;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        
        .saving-indicator.show {
            display: flex;
        }
        
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Scrollbar */
        .customizer-controls::-webkit-scrollbar {
            width: 8px;
        }
        
        .customizer-controls::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        
        .customizer-controls::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .customizer-controls::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="customizer-container">
        <!-- Sidebar -->
        <div class="customizer-sidebar">
            <div class="customizer-header">
                <h1>🎨 Theme Customizer</h1>
                <button type="button" class="close-customizer" onclick="window.location.href='/admin/themes'">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Close
                </button>
            </div>
            
            <div class="theme-info">
                <h2>{{ $theme->name }}</h2>
                <p>{{ $theme->description }}</p>
                @if($theme->is_active)
                    <span class="theme-badge">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active Theme
                    </span>
                @endif
            </div>
            
            <div class="customizer-controls">
                <form id="customizer-form">
                    @csrf
                    
                    <!-- Site Identity -->
                    <div class="accordion active">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span>🏠 Site Identity</span>
                            <svg class="accordion-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="accordion-content">
                            <div class="form-group">
                                <label class="form-label">Site Title</label>
                                <input type="text" name="site_title" class="form-input" value="{{ $settings['site_title'] }}" onchange="autoSave()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tagline</label>
                                <input type="text" name="site_tagline" class="form-input" value="{{ $settings['site_tagline'] }}" onchange="autoSave()">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colors -->
                    <div class="accordion active">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span>🎨 Colors</span>
                            <svg class="accordion-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="accordion-content">
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <input type="color" name="primary_color" class="form-color" value="{{ $settings['primary_color'] }}" onchange="autoSave()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Accent Color</label>
                                <input type="color" name="accent_color" class="form-color" value="{{ $settings['accent_color'] }}" onchange="autoSave()">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hero Section -->
                    <div class="accordion active">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span>🖼️ Hero Section</span>
                            <svg class="accordion-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="accordion-content">
                            <div class="form-group">
                                <label class="form-label">Hero Title</label>
                                <input type="text" name="hero_title" class="form-input" value="{{ $settings['hero_title'] }}" onchange="autoSave()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Hero Subtitle</label>
                                <input type="text" name="hero_subtitle" class="form-input" value="{{ $settings['hero_subtitle'] }}" onchange="autoSave()">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Hero Description</label>
                                <textarea name="hero_description" class="form-textarea" onchange="autoSave()">{{ $settings['hero_description'] }}</textarea>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Primary Button Text</label>
                                    <input type="text" name="hero_primary_button_text" class="form-input" value="{{ $settings['hero_primary_button_text'] }}" onchange="autoSave()">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Primary Button URL</label>
                                    <input type="text" name="hero_primary_button_url" class="form-input" value="{{ $settings['hero_primary_button_url'] }}" onchange="autoSave()">
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Secondary Button Text</label>
                                    <input type="text" name="hero_secondary_button_text" class="form-input" value="{{ $settings['hero_secondary_button_text'] }}" onchange="autoSave()">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Secondary Button URL</label>
                                    <input type="text" name="hero_secondary_button_url" class="form-input" value="{{ $settings['hero_secondary_button_url'] }}" onchange="autoSave()">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span>📄 Footer</span>
                            <svg class="accordion-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="accordion-content">
                            <div class="form-group">
                                <label class="form-label">Footer Text</label>
                                <textarea name="footer_text" class="form-textarea" rows="3" onchange="autoSave()">{{ $settings['footer_text'] }}</textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Custom CSS -->
                    <div class="accordion">
                        <div class="accordion-header" onclick="toggleAccordion(this)">
                            <span>💻 Custom CSS</span>
                            <svg class="accordion-icon" width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="accordion-content">
                            <div class="form-group">
                                <label class="form-label">Additional CSS</label>
                                <textarea name="custom_css" class="form-textarea form-textarea-code" rows="8">{{ $settings['custom_css'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="customizer-footer">
                @if(!$theme->is_active)
                    <button type="button" class="btn btn-success" onclick="activateTheme()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Activate & Publish
                    </button>
                @else
                    <button type="button" class="btn btn-primary" onclick="saveSettings()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7.707 10.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V6h5a2 2 0 012 2v7a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2h5v5.586l-1.293-1.293zM9 4a1 1 0 012 0v2H9V4z"/>
                        </svg>
                        Save Changes
                    </button>
                @endif
            </div>
        </div>
        
        <!-- Preview -->
        <div class="customizer-preview">
            <div class="preview-header">
                <div class="preview-header-left">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <span class="preview-header-title">Live Preview</span>
                </div>
                
                <div class="preview-actions">
                    <div class="device-toggle">
                        <button type="button" class="device-btn active" data-device="desktop" onclick="setDevice('desktop')" title="Desktop View">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button type="button" class="device-btn" data-device="tablet" onclick="setDevice('tablet')" title="Tablet View">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                        </button>
                        <button type="button" class="device-btn" data-device="mobile" onclick="setDevice('mobile')" title="Mobile View">
                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <button type="button" class="icon-btn" onclick="refreshPreview()" title="Refresh Preview">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="preview-container">
                <div class="preview-frame-wrapper" id="preview-wrapper">
                    <iframe id="preview-frame" src="{{ $previewUrl }}" sandbox="allow-same-origin allow-scripts allow-popups allow-forms"></iframe>
                </div>
            </div>
        </div>
    </div>
    
    <div class="saving-indicator" id="saving-indicator">
        <div class="spinner"></div>
        <span id="saving-text">Saving changes...</span>
    </div>
    
    <script>
        let saveTimeout;
        const themeId = {{ $theme->id }};
        
        function toggleAccordion(header) {
            const accordion = header.parentElement;
            accordion.classList.toggle('active');
        }
        
        function setDevice(device) {
            const buttons = document.querySelectorAll('.device-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            const activeBtn = document.querySelector(`[data-device="${device}"]`);
            if (activeBtn) activeBtn.classList.add('active');
            
            const wrapper = document.getElementById('preview-wrapper');
            wrapper.classList.remove('tablet-view', 'mobile-view');
            
            if (device === 'tablet') {
                wrapper.classList.add('tablet-view');
            } else if (device === 'mobile') {
                wrapper.classList.add('mobile-view');
            }
        }
        
        function refreshPreview() {
            const iframe = document.getElementById('preview-frame');
            iframe.contentWindow.location.reload();
        }
        
        function showSavingIndicator(text = 'Saving changes...', success = false) {
            const indicator = document.getElementById('saving-indicator');
            const textEl = document.getElementById('saving-text');
            
            textEl.textContent = text;
            indicator.classList.add('show');
            
            if (success) {
                indicator.style.background = '#10b981';
                setTimeout(() => {
                    indicator.classList.remove('show');
                    setTimeout(() => {
                        indicator.style.background = '#1e293b';
                    }, 300);
                }, 2000);
            }
        }
        
        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                saveSettings(true);
            }, 1000);
        }
        
        function saveSettings(silent = false) {
            if (!silent) {
                showSavingIndicator('Saving changes...');
            }
            
            const form = document.getElementById('customizer-form');
            const formData = new FormData(form);
            
            // Convert FormData to JSON object for better handling
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            fetch(`/theme-customizer/${themeId}/save`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (!silent) {
                        showSavingIndicator('✓ Changes saved!', true);
                    }
                    setTimeout(() => refreshPreview(), 500);
                } else {
                    showSavingIndicator('❌ ' + (data.message || 'Error saving'), true);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSavingIndicator('❌ Error saving', true);
            });
        }
        
        function activateTheme() {
            if (!confirm('Activate this theme and make it live on your website?')) {
                return;
            }
            
            showSavingIndicator('Activating theme...');
            
            // First save settings
            const form = document.getElementById('customizer-form');
            const formData = new FormData(form);
            
            // Convert FormData to JSON object
            const data = {};
            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }
            
            fetch(`/theme-customizer/${themeId}/save`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Then activate
                    return fetch(`/theme-customizer/${themeId}/activate`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to save settings');
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSavingIndicator('✓ Theme activated!', true);
                    setTimeout(() => {
                        window.location.href = data.redirect || '/admin/themes';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to activate theme');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSavingIndicator('❌ ' + error.message, true);
            });
        }
    </script>
</body>
</html>
