import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import VueLazyload from 'vue-lazyload';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import base from './base'
import i18n from "@/i18n.js";
import Toast, { POSITION } from 'vue-toastification';
import 'vue-toastification/dist/index.css';
const options = {
    position: POSITION.TOP_RIGHT,
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: 'button',
    icon: true,
    rtl: true,
};
createInertiaApp({

    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/Admin/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('Link',Link)
            .component('Head',Head)
            .use(Toast,options)
            .use(VueLazyload, {
                preLoad: 1.3,     // Optional configuration
                // loading: '/path/to/loading.gif', // Optional: path to loading image
                attempt: 1,       // Optional: number of image load attempts
              })
            .mixin(base)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',

    },
});
