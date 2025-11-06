import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
    tailwindcss(),
  ],
  host: '0.0.0.0', // Listen on all network interfaces
  server: {
    host: 'localhost',
    watch: {
      usePolling: true,
      interval: 300, // ms
    },
  },
});
