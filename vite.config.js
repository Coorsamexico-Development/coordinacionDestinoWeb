import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',        // ← Permite conexiones desde fuera del contenedor
        port: 5173,             // ← Puerto predeterminado de Vite
        hmr: {
            protocol: 'ws',
            host: 'localhost',   // ← Host para hot module replacement
            port: 5173,
        },
        watch: {
            usePolling: true,    // ← Importante: habilita polling para Docker
            interval: 1000,      // ← Intervalo de polling en ms
        },
    },
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
