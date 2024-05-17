import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
<<<<<<< HEAD
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
=======
            input: ['resources/css/app.css', 'resources/js/app.js'],
>>>>>>> db327f805d329124414310ad257da63d5a139e1e
            refresh: true,
        }),
    ],
});
