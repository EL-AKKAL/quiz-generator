import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    notifications: Array<any>;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    appVersion: string;
    lastUpdated: string;
    questionTypes: Array<string>;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Option {
    id: number;
    text: string;
}
export interface Question {
    id: number;
    quiz_id: number;
    type: string;
    question: string;
    description: string | null;
    data: {
        options?: Array<Option>;
    };
    created_at: string;
    updated_at: string;
}
export interface IQuiz {
    id: number;
    title: string;
    description: string;
    created_at: string;
    updated_at: string;
    slug: string;
    status: boolean;
    expire_date: string | null;
    picture?: string;
    questions: Array<Question>;
    questions_count: number;
}

export type Paginator<T> = {
    data: T[];
    first_page_url: string;
    last_page_url: string;
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: PaginationLink[];
    next_page_url: string | null;
    prev_page_url: string | null;
};

export type BreadcrumbItemType = BreadcrumbItem;
