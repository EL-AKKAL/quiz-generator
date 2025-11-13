<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const page = usePage();
const totalQuizzes = computed(() => page.props.totalQuizzes);
const totalAnswers = computed(() => page.props.totalAnswers);
const totalActiveQuizzes = computed(() => page.props.totalActiveQuizzes);
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl py-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative flex aspect-video flex-col items-center justify-center gap-3 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <h3 class="text-2xl font-semibold">Total Quizzes</h3>
                    <div class="text-7xl !font-black">
                        {{ totalQuizzes }}
                    </div>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <div
                        class="relative flex aspect-video flex-col items-center justify-center gap-3 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                    >
                        <h3 class="text-2xl font-semibold">Total Answers</h3>
                        <div class="text-7xl !font-black">
                            {{ totalAnswers }}
                        </div>
                    </div>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                >
                    <div
                        class="relative flex aspect-video flex-col items-center justify-center gap-3 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
                    >
                        <h3 class="text-2xl font-semibold">Active Quizzes</h3>
                        <div class="text-7xl !font-black">
                            {{ totalActiveQuizzes }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
