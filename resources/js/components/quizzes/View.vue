<script setup lang="ts">
import QuestionViewer from '@/components/questions/QuestionViewer.vue';
import QuizPicture from '@/components/quizzes/ui/QuizPicture.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import { IQuiz } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const page = usePage();
const quiz: IQuiz = reactive({ ...(page.props.quiz as IQuiz) });
</script>
<template>
    <div class="w-full max-w-3xl gap-4 !p-5 pt-0">
        <h1 class="py-5 text-center text-4xl font-black">
            {{ quiz.title }}
        </h1>
        <div class="relative h-96">
            <QuizPicture
                class="h-full w-full rounded-none !object-cover"
                :picture="quiz.picture"
                :title="quiz.title"
            />
        </div>
        <h2 class="py-5 text-center text-2xl !font-semibold">
            {{ quiz.description }}
        </h2>
        <Separator />

        <QuestionViewer :quiz="quiz" />
    </div>
</template>
