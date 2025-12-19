import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import fs from 'fs';

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
  // resolve: {
  //   alias: {
  //   }
  // },

  // server: {
      // cors: true,
      // host: "0.0.0.0",
      // port: 5173,
    // https: {
    //   key: fs.readFileSync('docker/certs/key.pem'),
    //   cert: fs.readFileSync('docker/certs/cert.pem'),
    // },
    // hmr: {
    //     host: 'localhost',
    //     protocol: 'wss'
    // }
  // }

});
