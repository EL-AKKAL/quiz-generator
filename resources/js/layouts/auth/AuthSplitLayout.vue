<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import DemoUser from '@/components/DemoUser.vue';
import GithubLink from '@/components/GithubLink.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import AuthBackground from './AuthBackground.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <div
            class="relative hidden h-screen flex-col overflow-hidden bg-muted p-10 lg:flex dark:border-r"
        >
            <AuthBackground />
            <div class="flex w-full items-center justify-between">
                <AppLogo />
                <div class="flex items-center justify-between gap-2">
                    <GithubLink />
                    <ThemeSwitcher />
                </div>
            </div>
            <DemoUser />
        </div>
        <div class="lg:p-8">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]"
            >
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-muted-foreground" v-if="description">
                        {{ description }}
                    </p>
                    <DemoUser class="block lg:hidden" />
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
