import './bootstrap';
import '../css/app.css';
import '../sass/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // Updated resolve function using import.meta.glob
    resolve: name => import.meta.glob('./Pages/**/*.vue')[`./Pages/${name}.vue`],
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.use(ZiggyVue, Ziggy);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
