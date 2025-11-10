<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
</script>

<template>
    <div class="container mx-auto flex min-h-screen flex-col md:max-w-2/3">
        <div class="flex w-full items-start justify-between py-4">
            <div class="flex w-fit flex-col items-center justify-center">
                <AppLogo href="#" class="h-10 !w-40" />
            </div>
        </div>
        <slot />
    </div>
</template>
