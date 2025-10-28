<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Form from '@/components/quizzes/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { IQuiz, type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const quiz: IQuiz = page.props.quiz as IQuiz;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: quiz.id ? `Edit ${quiz.title}` : 'New Quiz',
        href: quiz.id
            ? QuizController.edit.url({ quiz: quiz.id })
            : QuizController.create.url(),
    },
];
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form :key="quiz.id" />
    </AppLayout>
</template>
