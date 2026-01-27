import { defineConfig } from 'vite'

export default defineConfig({
    publicDir: false,
    build: {
        manifest: true,
        outDir: 'public/build',
        rollupOptions: {
            input: [
                'resources/scss/main.scss',
                'resources/js/category.js'
            ],
        },
    },
    server: {
        origin: 'http://localhost:5173',
    },
})