{{-- Asset verification hook --}}
<script>
    console.log('Filament Assets Check:', {
        baseUrl: '{{ config("app.url") }}',
        assetUrl: '{{ config("app.asset_url") }}',
        filamentPath: '{{ filament()->getPath() }}'
    });
</script>
