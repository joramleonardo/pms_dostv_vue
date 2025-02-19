import './bootstrap';
import '../css/app.css';

import { createApp, h, onMounted } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Import Preline.js
import 'preline';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        vueApp.mount(el);

        // Ensure Preline components are initialized after Vue mounts
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                if (window.HSStaticMethods) {
                    window.HSStaticMethods.autoInit();
                }
            }, 300); // Delay to ensure DOM is fully rendered
        });

        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
