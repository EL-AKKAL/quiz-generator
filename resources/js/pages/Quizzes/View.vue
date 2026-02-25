<script setup lang="ts">
import View from '@/components/quizzes/View.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { IQuiz } from '@/types';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const quiz = page.props.quiz as IQuiz;
</script>
<template>
    <GuestLayout>
        <View :quiz />
    </GuestLayout>
</template>
