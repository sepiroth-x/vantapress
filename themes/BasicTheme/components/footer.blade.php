<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>{{ config('app.name') }}</h4>
                <p>A powerful CMS built with Laravel</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Resources</h4>
                <ul>
                    <li><a href="/docs">Documentation</a></li>
                    <li><a href="/support">Support</a></li>
                    <li><a href="/admin">Admin Panel</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin-top: 0.5rem; font-size: 0.875rem; opacity: 0.8;">
                Theme by <strong>Sepiroth X Villainous</strong> | Open Source | 
                <a href="https://github.com/sepiroth-x" target="_blank" style="color: inherit;">GitHub</a> | 
                <a href="https://www.facebook.com/sepirothx/" target="_blank" style="color: inherit;">Facebook</a> | 
                <a href="https://x.com/sepirothx000" target="_blank" style="color: inherit;">Twitter</a>
            </p>
        </div>
    </div>
</footer>
