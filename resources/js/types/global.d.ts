import type { route as ZiggyRoute } from '../../../vendor/tightenco/ziggy/src/js';

declare global {
    const route: typeof ZiggyRoute;

    interface Window {
        translations: Record<string, string>;
        Ziggy?: {
            url: string;
            port: number | null;
            defaults: Record<string, unknown>;
            routes: Record<string, unknown>;
        };
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ZiggyRoute;
        __: (key: string, replacements?: Record<string, string>) => string;
    }
}

export {};
