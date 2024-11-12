import './bootstrap';
import '../css/app.css'
import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import base from './base'
import i18n from "@/i18n.js";
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import VueLazyload from 'vue-lazyload';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(i18n)
            .use(VueLazyload, {
                preLoad: 1.3,     // Optional configuration
                // loading: '/path/to/loading.gif', // Optional: path to loading image
                attempt: 1,       // Optional: number of image load attempts
              })
            .component('Link',Link)
            .component('Head',Head)
            .mixin(base)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
