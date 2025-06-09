// resources/js/app.tsx
import '@shopify/polaris/build/esm/styles.css';
import '../css/app.css';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import createApp from '@shopify/app-bridge';
import { getSessionToken } from '@shopify/app-bridge/utilities';
import axios, { AxiosHeaders } from 'axios';
import { AppProvider, Frame, Navigation } from '@shopify/polaris';

const params = new URLSearchParams(window.location.search);
const host = params.get('host')!;
const appBridge = createApp({
    apiKey: import.meta.env.VITE_SHOPIFY_API_KEY!,
    host,
});

const csrfToken = document
  .querySelector('meta[name="csrf-token"]')!
  .getAttribute('content')!;

axios.interceptors.request.use(async (config) => {
    const token = await getSessionToken(appBridge);
    const headers = new AxiosHeaders(config.headers);
    headers.set('X-CSRF-TOKEN', `${csrfToken}`);
    headers.set('Authorization', `Bearer ${token}`);
    config.headers = headers;
    return config;
});

createInertiaApp({
    title: (title) => `${title} — ${import.meta.env.VITE_APP_NAME}`,
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <AppProvider
                i18n={{
                    Polaris: { /* any translations here */ },
                }}
            >
                <Frame>
                    <App {...props} />
                </Frame>
            </AppProvider>
        );
        // root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
