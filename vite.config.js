import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        manifest: true,
        outDir: 'web/dist',
        rollupOptions: {
            input: {
                'theme-main': path.resolve(__dirname, 'storeroom-theme/src/main.js'),
                'contact-main': path.resolve( __dirname, 'storeroom-src/assets/contact.js')
            },
        },
    },
    server: {
        cors: true,
        strictPort: true,
        port: 3000,
    },
});