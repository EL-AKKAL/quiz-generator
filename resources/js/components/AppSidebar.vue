<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import NavMain from '@/components/NavMain.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, logout } from '@/routes';
import { edit } from '@/routes/profile';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import {
    Award,
    BadgePlus,
    LayoutGrid,
    LogOut,
    Settings,
} from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import Button from './ui/button/Button.vue';
import UserInfo from './UserInfo.vue';

const page = usePage();
const user = page.props.auth.user;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Quizzes',
        href: QuizController.index.url(),
        icon: Award,
    },
    {
        title: 'Create Quiz',
        href: QuizController.create.url(),
        icon: BadgePlus,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Settings',
        href: edit(),
        icon: Settings,
    },
    {
        title: 'Logout',
        href: logout(),
        icon: LogOut,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <Button
                variant="ghost"
                size="lg"
                class="p-4 data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                data-test="sidebar-menu-button"
            >
                <UserInfo :user="user" :show-email="true" />
            </Button>
            <NavMain :show-title="false" :items="footerNavItems" />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
