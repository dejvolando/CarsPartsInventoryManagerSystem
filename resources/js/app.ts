import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { createToastflow, ToastContainer } from 'vue-toastflow'
import '../css/app.css';
import 'bootstrap'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createToastflow({
                position: 'top-center',
                alignment: 'left',
                progressAlignment: 'right-to-left',
                offset: '16px',
                gap: '8px',
                width: '350px',
                zIndex: 9999,
                overflowScroll: false,
                duration: 2000,
                maxVisible: 5,
                preventDuplicates: false,
                order: 'newest',
                progressBar: true,
                pauseOnHover: true,
                pauseStrategy: 'resume',
                closeButton: true,
                closeOnClick: false,
                supportHtml: false,
                showCreatedAt: false,
            }))
            .component('ToastContainer', ToastContainer)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
