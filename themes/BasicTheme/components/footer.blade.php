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
            <p class="footer-attribution">
                Theme by <strong>Sepiroth X Villainous</strong> | Open Source | 
                <a href="https://github.com/sepiroth-x" target="_blank">GitHub</a> | 
                <a href="https://www.facebook.com/sepirothx/" target="_blank">Facebook</a> | 
                <a href="https://x.com/sepirothx000" target="_blank">Twitter</a>
            </p>
        </div>
    </div>
</footer>
