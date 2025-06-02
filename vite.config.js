import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js'

            ],
            refresh: true,
        }),
 
    ],
    resolve: {
    alias: {
      jquery: 'jquery/src/jquery', // pastikan ini ada
    },
    server: {
      cors: true
    }
  },
});
