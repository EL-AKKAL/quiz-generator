import '../css/app.css';

import { Toaster } from '@/components/ui/sonner';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import 'vue-sonner/style.css';
import { initializeTheme } from './composables/useAppearance';
import HighchartsVue from "highcharts-vue";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(HighchartsVue)
            .mount(el);

        const toaster = document.createElement('div');
        document.body.appendChild(toaster);
        createApp(Toaster, { richColors: true }).mount(toaster);
    },
    progress: {
        color: '#4B5563',
    },
});

initializeTheme();
