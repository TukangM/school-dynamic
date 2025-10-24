import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/react/editor.jsx',
      ],
      refresh: true,
    }),
    react(),
  ],
  server: {
    host: true,      // equivalent to '0.0.0.0'
    port: 5173,
    strictPort: true,
    hmr: { 
      host: 'localhost',
      protocol: 'ws' 
    },
    cors: true,
  },
})
