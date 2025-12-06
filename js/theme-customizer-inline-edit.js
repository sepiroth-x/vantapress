/**
 * VantaPress Theme Customizer - Inline Editing
 * Click directly on text to edit it (page builder style)
 */

class ThemeCustomizerInlineEdit {
    constructor(options = {}) {
        this.customizerWindow = options.customizerWindow || window.parent;
        this.editableAttr = options.editableAttr || 'data-vp-editable';
        this.activeElements = [];
        this.maxElements = options.maxElements || 300;
        this.currentlyEditing = null;
        
        this.init();
    }

    init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        try {
            console.log('🎨 Initializing inline editing...');
            
            // Inject styles
            this.injectStyles();
            
            // Discover editable elements
            this.discoverEditableElements();
            
            // Make elements inline editable
            this.enableInlineEditing();
            
            // Send elements to customizer
            this.sendDetectedElementsToParent();
            
            // Listen for customizer events
            this.setupCustomizerBridge();
            
            console.log(`✓ Inline editing enabled for ${this.activeElements.length} elements`);
        } catch (error) {
            console.error('Error initializing inline editing:', error);
        }
    }

    injectStyles() {
        const style = document.createElement('style');
        style.id = 'vp-inline-edit-styles';
        style.textContent = `
            /* Inline Editable Styles */
            [data-vp-editable] {
                position: relative;
                transition: outline 0.15s ease, background 0.15s ease;
            }

            /* Hover state - only when NOT editing */
            [data-vp-editable]:not(.vp-editing):hover {
                outline: 2px dashed #3b82f6;
                outline-offset: 2px;
                cursor: text;
                background: rgba(59, 130, 246, 0.05);
            }

            /* Editing state - no hover effects */
            [data-vp-editable].vp-editing {
                outline: 2px solid #10b981 !important;
                outline-offset: 2px;
                background: rgba(16, 185, 129, 0.05) !important;
                cursor: text;
            }

            /* Edit Label - only when NOT editing */
            [data-vp-editable]:not(.vp-editing):hover::before {
                content: '✏️ Click to edit';
                position: absolute;
                top: -24px;
                left: 0;
                background: #3b82f6;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 600;
                white-space: nowrap;
                z-index: 9999;
                pointer-events: none;
            }

            /* Editing label - stays visible */
            [data-vp-editable].vp-editing::before {
                content: '💾 Editing... (Click outside to save)';
                position: absolute;
                top: -24px;
                left: 0;
                background: #10b981;
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 11px;
                font-weight: 600;
                white-space: nowrap;
                z-index: 9999;
                pointer-events: none;
            }

            /* Disable hover on child elements when parent is being edited */
            [data-vp-editable].vp-editing * {
                outline: none !important;
            }

            /* Container hover styles */
            [data-vp-container]:hover {
                outline: 1px dashed #6366f1;
                outline-offset: 4px;
            }
        `;
        document.head.appendChild(style);
    }

    discoverEditableElements() {
        // Focus on text-containing elements only
        const textSelectors = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'span', 'a', 'button', 'label',
            'li', 'td', 'th', 'figcaption', 'blockquote'
        ];

        const allElements = document.querySelectorAll(textSelectors.join(', '));
        let count = 0;

        allElements.forEach((element, index) => {
            if (count >= this.maxElements) return;
            
            // Skip if no text content
            const text = element.textContent.trim();
            if (!text || text.length === 0) return;
            
            // Skip if hidden
            if (this.isHidden(element)) return;
            
            // Skip if inside script/style
            if (element.closest('script, style, svg, noscript')) return;
            
            // Generate unique ID
            const elementId = this.generateElementId(element, index);
            element.setAttribute(this.editableAttr, elementId);
            
            // Store element data
            this.activeElements.push({
                id: elementId,
                element: element,
                type: this.getElementType(element),
                label: this.generateLabel(element),
                tagName: element.tagName.toLowerCase(),
                originalContent: element.textContent
            });
            
            count++;
        });
        
        // Also detect color elements (elements with color/background-color styles)
        this.detectColorElements();
    }

    detectColorElements() {
        // Find elements with inline colors or CSS color properties
        const allElements = document.querySelectorAll('*');
        
        allElements.forEach((element, index) => {
            if (this.isHidden(element)) return;
            if (element.closest('script, style, svg, noscript')) return;
            if (element.hasAttribute(this.editableAttr)) return; // Skip already marked
            
            const style = window.getComputedStyle(element);
            const bgColor = style.backgroundColor;
            const textColor = style.color;
            
            // Check if element has a significant background color (not transparent/default)
            const hasSignificantBg = bgColor && bgColor !== 'rgba(0, 0, 0, 0)' && bgColor !== 'transparent';
            
            // Only detect elements that are likely to be styled containers
            const isStyledContainer = hasSignificantBg && (
                element.classList.length > 0 ||
                ['header', 'footer', 'nav', 'section', 'aside', 'div'].includes(element.tagName.toLowerCase())
            );
            
            if (isStyledContainer) {
                const elementId = this.generateElementId(element, index + 10000);
                element.setAttribute(this.editableAttr, elementId);
                element.setAttribute('data-vp-color-element', 'true');
                
                this.activeElements.push({
                    id: elementId,
                    element: element,
                    type: 'color',
                    label: `Color: ${this.generateLabel(element)}`,
                    tagName: element.tagName.toLowerCase(),
                    colorValue: bgColor
                });
            }
        });
    }

    isHidden(element) {
        try {
            const style = window.getComputedStyle(element);
            return style.display === 'none' || 
                   style.visibility === 'hidden' || 
                   style.opacity === '0' ||
                   element.offsetWidth === 0;
        } catch {
            return true;
        }
    }

    generateElementId(element, index) {
        // Use existing ID if available
        if (element.id) return element.id;
        
        // Use data-vp-element if exists
        if (element.hasAttribute('data-vp-element')) {
            return element.getAttribute('data-vp-element');
        }
        
        // Use class + index
        if (element.className && typeof element.className === 'string') {
            const firstClass = element.className.split(' ')[0];
            if (firstClass) return `${firstClass}_${index}`;
        }
        
        // Use tag + index
        return `${element.tagName.toLowerCase()}_${index}`;
    }

    getElementType(element) {
        const tag = element.tagName.toLowerCase();
        if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tag)) return 'heading';
        if (tag === 'a') return 'link';
        if (tag === 'button') return 'button';
        return 'text';
    }

    generateLabel(element) {
        const text = element.textContent.trim().substring(0, 40);
        const tag = element.tagName.toLowerCase();
        
        if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tag)) {
            return `${tag.toUpperCase()}: ${text}`;
        }
        
        return text || tag;
    }

    enableInlineEditing() {
        this.activeElements.forEach(({ element, id }) => {
            // Make contenteditable on click
            element.addEventListener('click', (e) => {
                // Only start editing if not already editing
                if (!element.classList.contains('vp-editing')) {
                    e.preventDefault();
                    e.stopPropagation();
                    this.startEditing(element, id);
                }
            });
            
            // Prevent hover effects from propagating when editing
            element.addEventListener('mouseenter', (e) => {
                if (this.currentlyEditing && this.currentlyEditing !== element) {
                    e.stopPropagation();
                }
            });
            
            // If element has children, make them editable too
            this.makeChildrenEditable(element, id);
        });
    }

    makeChildrenEditable(parentElement, parentId) {
        // Get direct text-containing children
        const children = parentElement.querySelectorAll('span, a, strong, em, b, i, small');
        
        children.forEach((child, index) => {
            const text = child.textContent.trim();
            if (text && text.length > 0 && !child.hasAttribute(this.editableAttr)) {
                const childId = `${parentId}_child_${index}`;
                child.setAttribute(this.editableAttr, childId);
                
                // Add to active elements
                this.activeElements.push({
                    id: childId,
                    element: child,
                    type: 'text',
                    label: `${this.generateLabel(parentElement)} > ${child.tagName.toLowerCase()}`,
                    tagName: child.tagName.toLowerCase(),
                    originalContent: child.textContent,
                    parent: parentElement
                });
                
                // Make it clickable
                child.addEventListener('click', (e) => {
                    if (!child.classList.contains('vp-editing')) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.startEditing(child, childId);
                    }
                });
            }
        });
    }

    startEditing(element, elementId) {
        // Disable other editing
        if (this.currentlyEditing && this.currentlyEditing !== element) {
            this.stopEditing(this.currentlyEditing);
        }

        // Don't restart if already editing this element
        if (element.classList.contains('vp-editing')) {
            return;
        }

        // Store original content
        if (!element.dataset.originalContent) {
            element.dataset.originalContent = element.textContent;
        }

        // Make editable
        element.contentEditable = true;
        element.classList.add('vp-editing');
        
        // Small delay to ensure class is applied before focus
        setTimeout(() => {
            element.focus();
            
            // Select all text
            const range = document.createRange();
            range.selectNodeContents(element);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }, 10);
        
        this.currentlyEditing = element;

        // Listen for blur (click outside)
        const blurHandler = (e) => {
            // Small delay to ensure we're really leaving the element
            setTimeout(() => {
                // Only save if we're no longer editing this element
                if (!element.contains(document.activeElement)) {
                    this.stopEditing(element);
                    this.saveChanges(element, elementId);
                    element.removeEventListener('blur', blurHandler);
                }
            }, 100);
        };
        
        element.addEventListener('blur', blurHandler);

        // Listen for Enter key
        const keydownHandler = (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                element.blur();
            }
            
            if (e.key === 'Escape') {
                element.textContent = element.dataset.originalContent;
                element.blur();
            }
        };
        
        element.addEventListener('keydown', keydownHandler);
        
        // Store handler for cleanup
        element._keydownHandler = keydownHandler;
    }

    stopEditing(element) {
        element.contentEditable = false;
        element.classList.remove('vp-editing');
        
        // Clean up event listener
        if (element._keydownHandler) {
            element.removeEventListener('keydown', element._keydownHandler);
            delete element._keydownHandler;
        }
        
        this.currentlyEditing = null;
    }

    saveChanges(element, elementId) {
        const newContent = element.textContent.trim();
        const originalContent = element.dataset.originalContent;

        if (newContent !== originalContent) {
            console.log(`💾 Saving changes for ${elementId}`);
            
            // Update stored content
            element.dataset.originalContent = newContent;
            
            // Notify parent window (this will save to DB without refreshing)
            this.notifyCustomizerOfChange(elementId, newContent);
            
            // Update customizer input
            this.updateCustomizerInput(elementId, newContent);
        }
    }

    notifyCustomizerOfChange(elementId, newContent) {
        try {
            window.parent.postMessage({
                type: 'vp-customizer-content-changed',
                elementId: elementId,
                content: newContent
            }, '*');
        } catch (error) {
            console.warn('Could not notify customizer:', error);
        }
    }

    updateCustomizerInput(elementId, newContent) {
        try {
            window.parent.postMessage({
                type: 'vp-customizer-update-input',
                elementId: elementId,
                value: newContent
            }, '*');
        } catch (error) {
            console.warn('Could not update input:', error);
        }
    }

    sendDetectedElementsToParent() {
        const organized = this.organizeElementsBySection();
        
        try {
            window.parent.postMessage({
                type: 'vp-customizer-elements-detected',
                elements: organized,
                totalCount: this.activeElements.length
            }, '*');
            
            console.log(`✓ Sent ${this.activeElements.length} editable elements to customizer`);
        } catch (error) {
            console.warn('Could not send elements to parent:', error);
        }
    }

    organizeElementsBySection() {
        const organized = {};

        this.activeElements.forEach(({ id, element, type, label, tagName }) => {
            let sectionId = 'content';
            let sectionLabel = 'Page Content';

            // Determine section based on parent container or type
            const header = element.closest('header');
            const footer = element.closest('footer');
            const nav = element.closest('nav');
            const hero = element.closest('.hero, [class*="hero"]');
            
            if (type === 'color') {
                sectionId = 'colors';
                sectionLabel = 'Colors & Backgrounds';
            } else if (header) {
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
            } else if (['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tagName)) {
                sectionId = 'headings';
                sectionLabel = 'Headings';
            }

            if (!organized[sectionId]) {
                organized[sectionId] = {
                    label: sectionLabel,
                    elements: []
                };
            }

            organized[sectionId].elements.push({
                id: id,
                type: type,
                label: label,
                tagName: tagName
            });
        });

        return organized;
    }

    setupCustomizerBridge() {
        window.addEventListener('message', (event) => {
            if (event.data.type === 'vp-customizer-update-element') {
                this.updateElementContent(event.data.elementId, event.data.value);
            }
        });
    }

    updateElementContent(elementId, value) {
        const item = this.activeElements.find(item => item.id === elementId);
        if (item) {
            item.element.textContent = value;
            item.element.dataset.originalContent = value;
        }
    }
}

// Auto-initialize if in iframe
if (window.self !== window.top) {
    if (!window.vpInlineEdit) {
        setTimeout(() => {
            if (!window.vpInlineEdit) {
                window.vpInlineEdit = new ThemeCustomizerInlineEdit();
            }
        }, 100);
    }
}

// Export
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ThemeCustomizerInlineEdit;
}
