import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/react/ArticleEditor.jsx',
      ],
      refresh: true,
    }),
    react(),
  ],
  build: {
    chunkSizeWarningLimit: 1200, // Increase to 1000 kB (default 500 kB)
    rollupOptions: {
      output: {
        manualChunks: {
          // Split vendor libraries into separate chunks
          'react-vendor': ['react', 'react-dom'],
          'editor-vendor': ['@uiw/react-md-editor', 'rehype-sanitize'],
          'zero-md-vendor': ['zero-md'],
        },
      },
    },
  },
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    hmr: { 
      host: 'localhost',
      protocol: 'ws' 
    },
    cors: true,
  },
})
