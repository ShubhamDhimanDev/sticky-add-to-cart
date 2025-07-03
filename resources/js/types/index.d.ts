import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface CartSettingsData {
    cart_bg_color: string;
    cart_text_color: string;
    cart_price_text_color: string;
    cart_position_from_bottom: number;
    btn_bg_color: string;
    btn_text_color: string;
    btn_onhover_bg_color: string;
    btn_onhover_text_color: string;
    buy_bg_color: string;
    buy_text_color: string;
    buy_onhover_bg_color: string;
    buy_onhover_text_color: string;
};

export type CartProps = {
    cartSettings: {
        data: CartSettingsData;
    };
    addExtensionLink: string;
};
