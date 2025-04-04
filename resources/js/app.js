import {createApp, createSSRApp, h} from 'vue';
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy.js';
import InstantSearch from 'vue-instantsearch/vue3/es';
import '../css/app.css';
import DefaultLayout from './Layouts/DefaultLayout.vue';
import clickOutsideDirective from '@/directives/clickOutsideDirective.js';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.bubble.css';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        console.log(name)
        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || DefaultLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .component('QuillEditor', QuillEditor)
            .use(InstantSearch)
            .directive('click-outside', clickOutsideDirective) // v-click-outside="isOpen=false"
            .mount(el);
    },
}).then(r => {
});
