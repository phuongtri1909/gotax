import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/assets/frontend/css/styles.css',
                'resources/assets/admin/css/styles_admin.css',
                'resources/assets/frontend/css/styles-header.css',
                'resources/assets/frontend/css/components/banner-home.css',
                'resources/assets/frontend/css/pages/home.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'thuviendohoa.local',
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: 'thuviendohoa.local',
            protocol: 'http',
            port: 5173,
        },
    },
    
    
});
