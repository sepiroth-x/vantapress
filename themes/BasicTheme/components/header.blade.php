<header class="site-header" data-vp-element="header">
    <div class="container">
        <div class="header-content">
            <div class="logo" data-vp-element="site_logo">
                <a href="/">{{ vp_get_theme_setting('site_title', config('app.name', 'VantaPress')) }}</a>
            </div>
            <nav class="main-nav" data-vp-element="primary_navigation">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </nav>
            <div class="header-actions">
                <a href="/admin" class="btn-admin">Admin</a>
            </div>
        </div>
    </div>
</header>
