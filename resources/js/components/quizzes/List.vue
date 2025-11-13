<script setup lang="ts">
import Empty from '@/components/Empty.vue';
import Quiz from '@/components/quizzes/Quiz.vue';
import PaginationComponent from '@/components/shared/PaginationComponent.vue';
import { IQuiz, Paginator } from '@/types';

defineProps<{ quizzes: Paginator<IQuiz> }>();
</script>
<template>
    <Empty v-if="!quizzes.data.length" unit="quizzes" />
    <div v-else class="grid w-full gap-4 py-5 md:grid-cols-2 lg:grid-cols-3">
        <Quiz v-for="quiz in quizzes.data" :key="quiz.id" :quiz="quiz" />
    </div>

    <PaginationComponent :data="quizzes" />
</template>
