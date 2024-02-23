import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';

export default defineConfig({
    base: '/dist/',
    plugins: [
        vue(),
        vuetify({ autoImport: true })
    ],
    build: {
        rollupOptions: {
            input: {
                dashboard: 'assets/dashboard.js',
            },
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            }
        },
        assetsInlineLimit: 0,
    },
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js',
        }
    }
});
