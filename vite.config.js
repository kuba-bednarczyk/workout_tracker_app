import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // To wymusza, żeby serwer deweloperski stylów zawsze był pod tym samym adresem
        host: 'localhost',
        hmr: {
            host: 'localhost',
        },
    },
});
