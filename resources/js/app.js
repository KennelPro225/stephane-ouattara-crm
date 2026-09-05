import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import reveal from './directives/reveal';

createInertiaApp({
    title: (title) => (title ? `${title} — Stéphane Ouattara` : 'Stéphane Ouattara'),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        // Ziggy's @routes blade directive defines window.route(); expose it as a
        // global property so templates can call route(...) directly, matching
        // how the ZiggyVue plugin would (this Ziggy version ships no Vue plugin).
        app.config.globalProperties.route = window.route;
        app.directive('reveal', reveal);
        app.use(plugin).mount(el);
    },
});
