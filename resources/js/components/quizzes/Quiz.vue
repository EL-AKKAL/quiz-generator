<script setup lang="ts">
import QuizPicture from '@/components/quizzes/ui/QuizPicture.vue';
import Badge from '@/components/ui/badge/Badge.vue';
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
            class="w-full5 min-h-[480px] max-w-sm overflow-hidden rounded-lg bg-white shadow-md"
        >
            <div class="relative">
                <QuizPicture
                    class="h-64 w-full rounded-none !object-cover"
                    :picture="quiz.picture"
                    :title="quiz.title"
                />
                <Actions :id="quiz.id" />
            </div>
            <div class="p-4">
                <div class="mb-2 flex items-start justify-between">
                    <div>
                        <h2
                            class="mb-1 line-clamp-1 text-xl font-semibold text-gray-800"
                        >
                            {{ quiz.title }}
                        </h2>
                    </div>
                </div>
                <div class="py-2">
                    <Badge :class="activeClass">
                        {{ activeStatus }}
                    </Badge>
                    <p class="py-2 text-sm text-gray-600">
                        expires on : {{ formatDate(quiz.expire_date) }}
                    </p>
                </div>
                <p class="mb-4 line-clamp-3 text-sm text-gray-600">
                    {{ quiz.description }}
                </p>
            </div>
        </div>
    </div>
</template>
