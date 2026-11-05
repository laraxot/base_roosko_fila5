import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // Demo server: disable Laravel's refresh watcher because the
            // monorepo contains recursive agent symlinks that chokidar cannot
            // safely traverse.
            refresh: false,
            publicDirectory: '../public_html',
        }),
    ],
    resolve: {
        alias: {
            '@theme-lit': path.resolve(__dirname, 'Modules/Geo/node_modules/lit'),
            '@theme-leaflet': path.resolve(__dirname, 'Modules/Geo/node_modules/leaflet'),
            '@theme-leaflet-css': path.resolve(__dirname, 'Modules/Geo/node_modules/leaflet/dist/leaflet.css'),
        },
    },
    server: {
        // The demo browser runs on the same machine. Binding to a wildcard
        // address makes the Laravel Vite plugin publish an unreachable
        // 0.0.0.0 asset URL in local pages.
        host: '127.0.0.1',
        // The repository contains recursive .claude/.agents symlinks. A demo
        // server does not need hot reload and chokidar can still stat those
        // links before an ignore rule is applied, causing ELOOP.
        watch: null,
    },
});
