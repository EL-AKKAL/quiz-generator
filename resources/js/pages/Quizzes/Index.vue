<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import List from '@/components/quizzes/List.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { IQuiz, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

defineProps<{ quizzes: Array<IQuiz> }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Quizzes',
        href: QuizController.index.url(),
    },
];
</script>
<template>
    <Head title="Quizzes" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <List :quizzes="quizzes" />
    </AppLayout>
</template>
