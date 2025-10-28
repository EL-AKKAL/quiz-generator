<script setup lang="ts">
import Empty from '@/components/Empty.vue';
import { IQuiz } from '@/types';
import Quiz from './Quiz.vue';

defineProps<{ quizzes: Array<IQuiz> }>();
</script>
<template>
    <Empty v-if="!quizzes.length" unit="quizzes" />
    <div v-else class="grid w-full gap-4 p-5 md:grid-cols-2 lg:grid-cols-3">
        <Quiz v-for="quiz in quizzes" :key="quiz.id" :quiz />
    </div>
</template>
