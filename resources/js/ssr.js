import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { createSSRApp, h } from 'vue';
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

createServer(page =>
    createInertiaApp({
        page,
        render: renderToString,
        resolve: name => {
            const page = require(`./Pages/${name}.vue`).default;
            page.layout = page.layout || DefaultLayout;
            return page
        },
        setup({ App, props, plugin }) {
            const app = createSSRApp({ render: () => h(App, props) });
            app.use(plugin);
            return app;
        },
    }),
);
