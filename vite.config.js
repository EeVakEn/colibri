import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwind from 'tailwindcss';
import tailwindConfig from './tailwind.config';
import vue from '@vitejs/plugin-vue';
import {resolve} from "path"; // импортируем плагин для Vue

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            ssr: 'resources/js/ssr.js',
        }),
        vue(
            {
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            },
        ),
        tailwind(tailwindConfig),
    ],
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources/js'),
            'ziggy-js': path.resolve('vendor/tightenco/ziggy'),
        },
    },
});
