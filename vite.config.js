import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

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
    }
  },
  server: {
    cors: true,
    host: '127.0.0.1',
  }
});
