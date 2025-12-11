@extends('theme.layouts::app')

@section('title', 'Home')

@section('content')
<div class="hero">
    <div class="container">
        <h1>Welcome to VantaPress</h1>
        <p>A powerful, modular CMS built with Laravel and FilamentPHP</p>
        <div class="hero-actions">
            <a href="#features" class="btn btn-primary">Explore Features</a>
            <a href="/admin" class="btn btn-secondary">Admin Panel</a>
        </div>
    </div>
</div>

<div class="features" id="features">
    <div class="container">
        <h2>Features</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">📦</div>
                <h3>Modular</h3>
                <p>Install and manage modules with ease using .vpm packages</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎨</div>
                <h3>Themeable</h3>
                <p>Customize your site with beautiful .vpt themes</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Fast</h3>
                <p>Built on Laravel for blazing-fast performance</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Secure</h3>
                <p>Enterprise-grade security out of the box</p>
            </div>
        </div>
    </div>
</div>
@endsection
