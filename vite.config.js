import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import autoprefixer from 'autoprefixer'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), autoprefixer(),
    ],
    server: {
    host: '0.0.0.0', // 🔥 bind ke semua interface IPv4
    port: 5173,
        strictPort: true,
    allowedHosts: [
      'localhost',
      '127.0.0.1',
      '192.168.1.10', // 🧠 ganti ke IP lokal kamu
      'your-live-share-id.liveshare.vsengsaas.visualstudio.com'
    ],
    hmr: {
      host: 'localhost', // 🧠 atau hostname kamu
      protocol: 'ws',
    },
  },
})
