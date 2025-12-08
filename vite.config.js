import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css'
            ],
            refresh: true,
            buildDirectory: 'build',
        }),
    ],
    build: {
        manifest: 'manifest.json', // Output manifest at build root, not .vite/
        outDir: 'build',
        rollupOptions: {
            output: {
                manualChunks: undefined,
            },
        },
    },
});
