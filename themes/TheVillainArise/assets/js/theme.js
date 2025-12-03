/**
 * The Villain Arise - Theme JavaScript
 * 
 * Interactive functionality for The Villain Arise theme.
 * 
 * @package TheVillainArise
 * @version 1.0.0
 * 
 * DEVELOPER NOTES:
 * - Vanilla JavaScript (no jQuery required)
 * - Mobile menu toggle
 * - Smooth scrolling
 * - Theme initialization
 */

(function() {
    'use strict';
    
    /**
     * Initialize theme on DOM ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initSmoothScroll();
        initLazyLoading();
        initTooltips();
        console.log('🦹 The Villain Arise theme initialized');
    });
    
    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const toggle = document.getElementById('mobile-menu-toggle');
        const menu = document.getElementById('mobile-menu');
        
        if (toggle && menu) {
            toggle.addEventListener('click', function() {
                menu.classList.toggle('hidden');
                
                // Update ARIA attribute
                const isExpanded = !menu.classList.contains('hidden');
                toggle.setAttribute('aria-expanded', isExpanded);
                
                // Animate icon
                const icon = toggle.querySelector('svg');
                if (icon) {
                    icon.style.transform = isExpanded ? 'rotate(90deg)' : 'rotate(0deg)';
                    icon.style.transition = 'transform 0.3s ease';
                }
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!toggle.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Close menu on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.focus();
                }
            });
        }
    }
    
    /**
     * Smooth Scrolling for Anchor Links
     */
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');
                
                // Skip if href is just '#'
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    e.preventDefault();
                    
                    // Smooth scroll to target
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Update URL without jumping
                    history.pushState(null, null, targetId);
                    
                    // Set focus to target for accessibility
                    targetElement.setAttribute('tabindex', '-1');
                    targetElement.focus();
                }
            });
        });
    }
    
    /**
     * Lazy Loading for Images
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }
    
    /**
     * Simple Tooltip System
     */
    function initTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', function() {
                const tooltipText = this.getAttribute('data-tooltip');
                const tooltip = document.createElement('div');
                tooltip.className = 'villain-tooltip';
                tooltip.textContent = tooltipText;
                tooltip.style.cssText = `
                    position: absolute;
                    background: #0f172a;
                    color: #dc2626;
                    padding: 0.5rem 1rem;
                    border-radius: 0.375rem;
                    font-size: 0.875rem;
                    border: 1px solid rgba(220, 38, 38, 0.3);
                    z-index: 9999;
                    pointer-events: none;
                    white-space: nowrap;
                `;
                
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
                
                this._tooltip = tooltip;
            });
            
            element.addEventListener('mouseleave', function() {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this._tooltip = null;
                }
            });
        });
    }
    
    /**
     * Utility: Debounce function
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    /**
     * Window Resize Handler (debounced)
     */
    window.addEventListener('resize', debounce(function() {
        // Handle responsive adjustments if needed
        console.log('Window resized');
    }, 250));
    
    /**
     * Console Easter Egg
     */
    console.log('%c🦹 THE VILLAIN ARISE 🦹', 'color: #dc2626; font-size: 20px; font-weight: bold; font-family: Orbitron, sans-serif;');
    console.log('%cPowered by VantaPress CMS', 'color: #6b7280; font-size: 12px;');
    console.log('%cDare to be different. Build something extraordinary.', 'color: #9ca3af; font-style: italic;');
    
})();
