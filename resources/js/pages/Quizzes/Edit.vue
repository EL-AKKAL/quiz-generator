<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Form from '@/components/quizzes/Form.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { IQuiz, type BreadcrumbItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const id = page.props.quiz ? (page.props.quiz as IQuiz).id : undefined;
const title = page.props.quiz ? (page.props.quiz as IQuiz).title : '';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: id ? `Edit ${title}` : 'New Quiz',
        href: id
            ? QuizController.edit.url({ quiz: id })
            : QuizController.create.url(),
    },
];
</script>
<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Form :key="id" />
    </AppLayout>
</template>
