import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

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
    host: '0.0.0.0',
    port: 5173,
    https: {
            key: fs.readFileSync('/var/www/nginx/ssl/server.key'),
            cert: fs.readFileSync('/var/www/nginx/ssl/server.crt'),
        },
    strictPort: true,
    watch: {
      usePolling: true
    },
    origin: 'https://10.129.78.59:5173',
    hmr: {
      host: '10.129.78.59',
      port: 5173,
    },
  }

});
