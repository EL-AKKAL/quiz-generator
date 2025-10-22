<script setup lang="ts">
import QuizItem from '@/components/QuizItem.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Quiz, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    quizzes: Array<Quiz>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: '/quizzes',
    },
];
</script>
<template>
    <Head title="Quizzes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid w-full gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">
            <QuizItem v-for="quiz in quizzes" :key="quiz.id" :item="quiz" />
        </div>
    </AppLayout>
</template>
