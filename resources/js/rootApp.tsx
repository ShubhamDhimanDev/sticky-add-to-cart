// resources/js/rootApp.tsx
import '@shopify/polaris/build/esm/styles.css';
import '../css/app.css';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(`./root/${name}.tsx`, import.meta.glob('./root/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    title: (title) => `${title} — My Site`,
    progress: {
        color: '#4B5563',
    },
});
