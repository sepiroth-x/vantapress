/**
 * VantaPress Theme Customizer - Live Preview Edit Icons
 * WordPress-style visual editing experience
 */

class ThemeCustomizerLiveEdit {
    constructor(options = {}) {
        this.customizerWindow = options.customizerWindow || window.parent;
        this.editIconClass = options.editIconClass || 'vp-edit-icon';
        this.editableAttr = options.editableAttr || 'data-customizer-element';
        this.hoverClass = options.hoverClass || 'vp-customizer-hover';
        this.activeElements = [];
        
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        // Inject edit icon styles
        this.injectStyles();
        
        // Find all editable elements
        this.discoverEditableElements();
        
        // Attach edit icons
        this.attachEditIcons();
        
        // Listen for customizer events
        this.setupCustomizerBridge();
    }

    injectStyles() {
        const style = document.createElement('style');
        style.id = 'vp-customizer-styles';
        style.textContent = `
            /* Edit Icon Styles */
            .vp-edit-icon {
                position: absolute;
                top: 8px;
                right: 8px;
                width: 32px;
                height: 32px;
                background: #dc2626;
                border: 2px solid #fff;
                border-radius: 4px;
                cursor: pointer;
                display: none;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
                z-index: 9999;
                transition: all 0.2s ease;
            }

            .vp-edit-icon:hover {
                background: #991b1b;
                transform: scale(1.1);
            }

            .vp-edit-icon svg {
                width: 16px;
                height: 16px;
                fill: white;
            }

            /* Editable Element Hover */
            [data-customizer-element]:hover {
                outline: 2px dashed #dc2626;
                outline-offset: 2px;
                position: relative;
            }

            [data-customizer-element]:hover .vp-edit-icon {
                display: flex !important;
            }

            .vp-customizer-hover {
                outline: 2px solid #dc2626;
                outline-offset: 2px;
            }

            /* Editable Badge */
            .vp-editable-badge {
                position: absolute;
                top: -10px;
                left: 8px;
                background: #dc2626;
                color: white;
                padding: 2px 8px;
                border-radius: 3px;
                font-size: 10px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                z-index: 9998;
                display: none;
            }

            [data-customizer-element]:hover .vp-editable-badge {
                display: block;
            }

            /* Prevent icon overlap */
            [data-customizer-element] {
                position: relative;
            }
        `;
        
        document.head.appendChild(style);
    }

    discoverEditableElements() {
        // Find elements with data-customizer-element attribute
        const elements = document.querySelectorAll(`[${this.editableAttr}]`);
        
        elements.forEach(element => {
            const elementId = element.getAttribute(this.editableAttr);
            this.activeElements.push({
                id: elementId,
                element: element,
                type: this.detectElementType(element),
            });
        });

        // Auto-detect common editable areas if not marked
        this.autoDetectEditableAreas();
    }

    autoDetectEditableAreas() {
        const commonSelectors = [
            { selector: 'header', id: 'header', label: 'Header' },
            { selector: '.hero, .hero-section', id: 'hero', label: 'Hero Section' },
            { selector: 'footer', id: 'footer', label: 'Footer' },
            { selector: '.site-title, h1.title', id: 'site_title', label: 'Site Title' },
            { selector: '.tagline, .site-tagline', id: 'site_tagline', label: 'Tagline' },
            { selector: 'nav, .navigation', id: 'navigation', label: 'Navigation' },
        ];

        commonSelectors.forEach(({ selector, id, label }) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                // Only add if not already marked
                if (!element.hasAttribute(this.editableAttr)) {
                    element.setAttribute(this.editableAttr, id);
                    this.activeElements.push({
                        id: id,
                        element: element,
                        type: this.detectElementType(element),
                        label: label,
                    });
                }
            });
        });
    }

    detectElementType(element) {
        const tagName = element.tagName.toLowerCase();
        
        if (tagName === 'img') return 'image';
        if (tagName === 'a') return 'link';
        if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tagName)) return 'heading';
        if (tagName === 'button') return 'button';
        if (element.classList.contains('color') || element.style.color || element.style.backgroundColor) return 'color';
        
        return 'text';
    }

    attachEditIcons() {
        this.activeElements.forEach(({ element, id, label }) => {
            // Create edit icon
            const icon = this.createEditIcon(id, label);
            
            // Create editable badge
            const badge = this.createEditableBadge(label || id);
            
            // Append to element
            element.appendChild(icon);
            element.appendChild(badge);
            
            // Add click handler
            icon.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.openCustomizerPanel(id);
            });
        });
    }

    createEditIcon(elementId, label) {
        const icon = document.createElement('div');
        icon.className = this.editIconClass;
        icon.title = `Edit ${label || elementId}`;
        icon.setAttribute('data-element-id', elementId);
        
        // Pencil icon SVG
        icon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
        `;
        
        return icon;
    }

    createEditableBadge(label) {
        const badge = document.createElement('div');
        badge.className = 'vp-editable-badge';
        badge.textContent = label || 'Editable';
        return badge;
    }

    openCustomizerPanel(elementId) {
        // Send message to parent customizer window
        if (this.customizerWindow && this.customizerWindow !== window) {
            this.customizerWindow.postMessage({
                type: 'vp-customizer-focus-element',
                elementId: elementId,
            }, '*');
        }
    }

    setupCustomizerBridge() {
        // Listen for messages from customizer
        window.addEventListener('message', (event) => {
            if (event.data.type === 'vp-customizer-update') {
                this.updateElement(event.data.elementId, event.data.value);
            } else if (event.data.type === 'vp-customizer-highlight') {
                this.highlightElement(event.data.elementId);
            }
        });
    }

    updateElement(elementId, value) {
        const items = this.activeElements.filter(item => item.id === elementId);
        
        items.forEach(({ element, type }) => {
            switch (type) {
                case 'text':
                case 'heading':
                    element.textContent = value;
                    break;
                case 'image':
                    element.src = value;
                    break;
                case 'link':
                    element.href = value;
                    break;
                case 'color':
                    element.style.color = value;
                    break;
                default:
                    element.textContent = value;
            }
        });
    }

    highlightElement(elementId) {
        // Remove previous highlights
        document.querySelectorAll(`.${this.hoverClass}`).forEach(el => {
            el.classList.remove(this.hoverClass);
        });

        // Add highlight to target
        const items = this.activeElements.filter(item => item.id === elementId);
        items.forEach(({ element }) => {
            element.classList.add(this.hoverClass);
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    refresh() {
        // Re-discover and re-attach
        this.activeElements = [];
        document.querySelectorAll(`.${this.editIconClass}`).forEach(icon => icon.remove());
        document.querySelectorAll('.vp-editable-badge').forEach(badge => badge.remove());
        
        this.discoverEditableElements();
        this.attachEditIcons();
    }
}

// Auto-initialize if in iframe
if (window.self !== window.top) {
    window.vpCustomizerLiveEdit = new ThemeCustomizerLiveEdit();
}

// Export for manual initialization
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeCustomizerLiveEdit;
}
