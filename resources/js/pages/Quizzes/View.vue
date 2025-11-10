<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import View from '@/components/quizzes/View.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { IQuiz, type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const quiz = page.props.quiz as IQuiz;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: quiz.title,
        href: QuizController.view.url({ slug: quiz.slug }),
    },
];
</script>
<template>
    <GuestLayout>
        <View :quiz />
    </GuestLayout>
</template>
