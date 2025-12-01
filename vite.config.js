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
                'resources/assets/frontend/css/components/breadcrumb.css',
                'resources/assets/frontend/css/components/title-page.css',
                'resources/assets/frontend/css/components/footer.css',
                'resources/assets/frontend/css/components/faq-section.css',
                'resources/assets/frontend/css/pages/home.css',
                'resources/assets/frontend/css/pages/account-settings.css',
                'resources/assets/frontend/css/components/tools-section.css',
                'resources/assets/frontend/css/components/smart-support-section.css',
                'resources/assets/frontend/css/components/statistics-section.css',
                'resources/assets/frontend/css/components/get-more-section.css',
                'resources/assets/frontend/css/components/testimonials-section.css',
                'resources/assets/frontend/css/components/cta-banner-section.css',
                'resources/assets/frontend/css/components/tool-hero.css',
                'resources/assets/frontend/css/components/features-section.css',
                'resources/assets/frontend/css/components/pricing-section.css',
                'resources/assets/frontend/css/components/pricing-bot-section.css',
                'resources/assets/frontend/css/pages/auth/login.css',
                'resources/assets/frontend/css/pages/contact.css',
                'resources/assets/frontend/css/pages/faqs.css',
                'resources/assets/frontend/css/pages/profile.css',
                'resources/assets/frontend/css/pages/error-pages.css',
                'resources/assets/frontend/css/pages/pricing.css',
                'resources/assets/frontend/css/pages/documentation.css',
                'resources/assets/frontend/css/pages/policy.css',
                'resources/assets/frontend/css/pages/tools/go-invoice.css',
                'resources/assets/frontend/css/pages/tools/go-invoice-trial.css',
                'resources/assets/frontend/css/components/payment/register-modal.css',
                'resources/assets/frontend/css/pages/tools/go-quick-upload.css',
                'resources/assets/frontend/css/pages/tools/go-soft.css',
                'resources/assets/frontend/css/pages/tools/go-invoice-tools.css',
                'resources/assets/frontend/css/pages/tools/go-bot.css',
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
