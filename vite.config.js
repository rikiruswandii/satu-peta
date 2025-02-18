import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css','resources/css/search.css','resources/css/article.css','resources/css/home.css', 'resources/js/app.js','resources/js/home.js', 'resources/js/explorer.js', 'resources/js/search.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '127.0.0.1',
        port: 5173,
        cors: true, // Mengizinkan CORS
    },
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'assets/[name]-[hash][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    define: {
        'process.env': {},
    },
    css: {
        devSourcemap: true,
    },
});
