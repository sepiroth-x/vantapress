<footer class="site-footer" data-vp-element="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4 data-vp-element="site_title">{{ vp_get_theme_setting('site_title', config('app.name')) }}</h4>
                <p data-vp-element="site_tagline">{{ vp_get_theme_setting('site_tagline', 'A powerful CMS built with Laravel') }}</p>
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
            <p data-vp-element="footer_text">
                {!! vp_get_theme_setting('footer_text', '&copy; ' . date('Y') . ' ' . config('app.name') . '. All rights reserved.') !!}
            </p>
            <p class="footer-attribution">
                Theme by <strong>Sepiroth X Villainous</strong> | Open Source | 
                <a href="https://github.com/sepiroth-x" target="_blank">GitHub</a> | 
                <a href="https://www.facebook.com/sepirothx/" target="_blank">Facebook</a> | 
                <a href="https://x.com/sepirothx000" target="_blank">Twitter</a>
            </p>
        </div>
    </div>
</footer>
