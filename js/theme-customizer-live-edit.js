/**
 * VantaPress Theme Customizer - Live Preview Edit Icons
 * WordPress-style visual editing experience
 */

class ThemeCustomizerLiveEdit {
    constructor(options = {}) {
        this.customizerWindow = options.customizerWindow || window.parent;
        this.editIconClass = options.editIconClass || 'vp-edit-icon';
        this.editableAttr = options.editableAttr || 'data-vp-element';
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
        
        // Send detected elements to parent
        this.sendDetectedElementsToParent();
        
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
            [data-vp-element]:hover {
                outline: 2px dashed #dc2626;
                outline-offset: 2px;
                position: relative;
            }

            [data-vp-element]:hover .vp-edit-icon {
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

            [data-vp-element]:hover .vp-editable-badge {
                display: block;
            }

            /* Prevent icon overlap */
            [data-vp-element] {
                position: relative;
            }
        `;
        
        document.head.appendChild(style);
    }

    discoverEditableElements() {
        // First, find elements explicitly marked with data-vp-element
        const markedElements = document.querySelectorAll(`[${this.editableAttr}]`);
        
        markedElements.forEach(element => {
            const elementId = element.getAttribute(this.editableAttr);
            this.activeElements.push({
                id: elementId,
                element: element,
                type: this.detectElementType(element),
                label: this.generateLabel(element, elementId),
            });
        });

        // Auto-detect ALL editable content on the page
        this.autoDetectAllEditableContent();
    }

    autoDetectAllEditableContent() {
        // Find ALL text-containing elements that should be editable
        const editableSelectors = [
            // Headings
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            // Text content
            'p', 'span', 'div', 'section', 'article',
            // Links and buttons
            'a', 'button',
            // Lists
            'li', 'ul', 'ol',
            // Images
            'img',
            // Other semantic elements
            'header', 'footer', 'nav', 'aside', 'main',
            'label', 'figcaption', 'blockquote', 'pre', 'code'
        ];

        const allElements = document.querySelectorAll(editableSelectors.join(', '));
        
        allElements.forEach((element, index) => {
            // Skip if already marked
            if (element.hasAttribute(this.editableAttr)) {
                return;
            }

            // Skip elements inside scripts, styles, or that are hidden
            if (this.shouldSkipElement(element)) {
                return;
            }

            // Skip empty elements (unless they're images or containers)
            const hasContent = this.elementHasContent(element);
            if (!hasContent && !['img', 'div', 'section', 'header', 'footer', 'nav'].includes(element.tagName.toLowerCase())) {
                return;
            }

            // Generate a unique ID for this element
            const elementId = this.generateElementId(element, index);
            
            // Mark the element
            element.setAttribute(this.editableAttr, elementId);
            
            // Add to active elements
            this.activeElements.push({
                id: elementId,
                element: element,
                type: this.detectElementType(element),
                label: this.generateLabel(element, elementId),
            });
        });
    }

    shouldSkipElement(element) {
        // Skip script, style, svg elements
        if (['script', 'style', 'svg', 'path'].includes(element.tagName.toLowerCase())) {
            return true;
        }

        // Skip if inside script or style
        if (element.closest('script, style, svg')) {
            return true;
        }

        // Skip if hidden
        const style = window.getComputedStyle(element);
        if (style.display === 'none' || style.visibility === 'hidden') {
            return true;
        }

        // Skip edit icons themselves
        if (element.classList.contains('vp-edit-icon') || element.classList.contains('vp-editable-badge')) {
            return true;
        }

        return false;
    }

    elementHasContent(element) {
        // Images always have content
        if (element.tagName.toLowerCase() === 'img') {
            return true;
        }

        // Check if element has text content (trim whitespace)
        const text = element.textContent.trim();
        if (text.length > 0) {
            return true;
        }

        // Check if element has child elements
        if (element.children.length > 0) {
            return true;
        }

        return false;
    }

    generateElementId(element, index) {
        // Try to use existing ID
        if (element.id) {
            return element.id;
        }

        // Try to use class name
        if (element.className && typeof element.className === 'string') {
            const firstClass = element.className.split(' ')[0];
            if (firstClass) {
                return `${firstClass}_${index}`;
            }
        }

        // Use tag name and index
        const tagName = element.tagName.toLowerCase();
        return `${tagName}_${index}`;
    }

    generateLabel(element, elementId) {
        const tagName = element.tagName.toLowerCase();
        
        // For headings, use the heading text
        if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tagName)) {
            const text = element.textContent.trim().substring(0, 30);
            return text || `${tagName.toUpperCase()} Heading`;
        }

        // For paragraphs, use first few words
        if (tagName === 'p') {
            const text = element.textContent.trim().substring(0, 30);
            return text ? `${text}...` : 'Paragraph';
        }

        // For links, use link text
        if (tagName === 'a') {
            const text = element.textContent.trim().substring(0, 30);
            return text ? `Link: ${text}` : 'Link';
        }

        // For images, use alt text or "Image"
        if (tagName === 'img') {
            return element.alt || 'Image';
        }

        // For buttons
        if (tagName === 'button') {
            const text = element.textContent.trim().substring(0, 30);
            return text ? `Button: ${text}` : 'Button';
        }

        // For other elements, use class or tag name
        if (element.className && typeof element.className === 'string') {
            const firstClass = element.className.split(' ')[0];
            if (firstClass) {
                return firstClass.replace(/-/g, ' ').replace(/_/g, ' ');
            }
        }

        return tagName.charAt(0).toUpperCase() + tagName.slice(1);
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
        this.sendDetectedElementsToParent();
    }

    sendDetectedElementsToParent() {
        // Organize elements by container/section
        const organizedElements = this.organizeElementsByContainer();
        
        // Send to parent window
        try {
            window.parent.postMessage({
                type: 'vp-customizer-elements-detected',
                elements: organizedElements,
                totalCount: this.activeElements.length
            }, '*');
            
            console.log(`✓ Sent ${this.activeElements.length} detected elements to customizer`);
        } catch (error) {
            console.warn('Could not send elements to parent:', error);
        }
    }

    organizeElementsByContainer() {
        const organized = {};
        
        this.activeElements.forEach(({ id, element, type, label }) => {
            // Determine which section this element belongs to
            let sectionId = 'content';
            let sectionLabel = 'Page Content';
            
            // Check parent containers
            const header = element.closest('header');
            const footer = element.closest('footer');
            const nav = element.closest('nav');
            const hero = element.closest('.hero, .hero-section, [class*="hero"]');
            const sidebar = element.closest('aside, .sidebar, [class*="sidebar"]');
            
            if (header) {
                sectionId = 'header';
                sectionLabel = 'Header';
            } else if (footer) {
                sectionId = 'footer';
                sectionLabel = 'Footer';
            } else if (nav) {
                sectionId = 'navigation';
                sectionLabel = 'Navigation';
            } else if (hero) {
                sectionId = 'hero';
                sectionLabel = 'Hero Section';
            } else if (sidebar) {
                sectionId = 'sidebar';
                sectionLabel = 'Sidebar';
            } else {
                // Try to determine by element type
                if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(element.tagName.toLowerCase())) {
                    sectionId = 'headings';
                    sectionLabel = 'Headings';
                } else if (element.tagName.toLowerCase() === 'img') {
                    sectionId = 'images';
                    sectionLabel = 'Images';
                } else if (element.tagName.toLowerCase() === 'a') {
                    sectionId = 'links';
                    sectionLabel = 'Links';
                } else if (element.tagName.toLowerCase() === 'button') {
                    sectionId = 'buttons';
                    sectionLabel = 'Buttons';
                }
            }
            
            // Initialize section if not exists
            if (!organized[sectionId]) {
                organized[sectionId] = {
                    label: sectionLabel,
                    elements: []
                };
            }
            
            // Add element to section
            organized[sectionId].elements.push({
                id: id,
                type: type,
                label: label,
                tagName: element.tagName.toLowerCase()
            });
        });
        
        return organized;
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
