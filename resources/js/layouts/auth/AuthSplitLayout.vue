<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import ThemeSwitcher from '@/components/ThemeSwitcher.vue';
import { usePage } from '@inertiajs/vue3';
import AuthBackground from './AuthBackground.vue';

const page = usePage();
const quote = page.props.quote;

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
                <ThemeSwitcher />
            </div>
            <div v-if="quote" class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="!z-50 text-lg">
                        &ldquo;Use demo user if you dont have an account&rdquo;
                    </p>
                    <footer class="text-sm">email: test@example.com</footer>
                    <footer class="text-sm">password: password</footer>
                </blockquote>
            </div>
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
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
