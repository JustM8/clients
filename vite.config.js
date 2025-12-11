import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/clients-show.js',
            ],
            refresh: true,
        }),
    ],

    // 🔧 додай цей блок
    server: {
        host: 'tinker.smarto', // твій локальний домен
        port: 5173,             // стандартний порт Vite
        cors: true,             // дозволяє запити з Laravel-домену
        hmr: {
            host: 'tinker.smarto', // щоб працювало live reload
        },
    },
});
