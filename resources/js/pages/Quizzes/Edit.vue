<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Form from '@/components/quizzes/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { IQuiz, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
const page = usePage();

const quiz = page.props.quiz ? (page.props.quiz as IQuiz) : undefined;

const title = quiz?.id ? `Edit : ${quiz.title}` : 'New Quiz';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: title,
        href: quiz?.id
            ? QuizController.edit.url({ quiz: quiz.id })
            : QuizController.create.url(),
    },
];
</script>
<template>
    <Head :title="title" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form :key="quiz?.id" />
    </AppLayout>
</template>
