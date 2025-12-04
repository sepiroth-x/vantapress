<!DOCTYPE html>
<html>
<head>
    <title>Livewire Test</title>
    @livewireStyles
</head>
<body>
    <h1>Testing Livewire Connection</h1>
    
    <div>
        <p>CSRF Token: <code>{{ csrf_token() }}</code></p>
        <p>Session ID: <code>{{ session()->getId() }}</code></p>
        <p>APP_URL: <code>{{ config('app.url') }}</code></p>
    </div>

    @livewireScripts
    <script>
        console.log('Livewire loaded:', typeof Livewire);
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content || 'NOT FOUND');
    </script>
</body>
</html>
