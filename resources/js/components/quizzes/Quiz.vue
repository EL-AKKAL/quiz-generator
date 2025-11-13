<script setup lang="ts">
import QuizPicture from '@/components/quizzes/ui/QuizPicture.vue';
import { formatDate } from '@/lib/utils';
import type { IQuiz } from '@/types';
import { computed, defineProps } from 'vue';
import Actions from './ui/Actions.vue';

const props = defineProps<{ quiz: IQuiz }>();

const activeClass = computed(() =>
    props.quiz.status
        ? 'bg-chart-2 text-destructive-foreground'
        : 'bg-destructive text-destructive-foreground',
);

const activeStatus = computed(() =>
    props.quiz.status ? 'Active' : 'Inactive',
);
</script>
<template>
    <div>
        <div
            class="min-h-[465px] w-full max-w-sm overflow-hidden rounded-lg border shadow-sm"
        >
            <div class="relative">
                <QuizPicture
                    class="h-64 w-full rounded-none !object-cover"
                    :picture="quiz.picture"
                    :title="quiz.title"
                />
                <Actions
                    :id="quiz.id"
                    :slug="quiz.slug"
                    :status="quiz.status"
                />
            </div>
            <div class="p-4">
                <div class="mb-2 flex items-start justify-between">
                    <div>
                        <h2 class="mb-1 line-clamp-1 text-xl font-semibold">
                            {{ quiz.title }}
                        </h2>
                    </div>
                </div>
                <div class="space-y-3 py-1">
                    <div class="flex w-full items-center justify-between">
                        <p class="text-sm font-medium text-foreground">
                            {{ quiz.questions_count }} Questions
                        </p>
                    </div>

                    <p
                        v-if="quiz.description"
                        class="line-clamp-3 text-sm text-muted-foreground"
                    >
                        {{ quiz.description }}
                    </p>
                    <p class="text-sm text-muted-foreground">
                        expires on : {{ formatDate(quiz.expire_date) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
