<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import QuizPicture from '@/components/quizzes/ui/QuizPicture.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import type { IQuiz } from '@/types';
import { Link } from '@inertiajs/vue3';
import { FilePenLine, Heart, Trash } from 'lucide-vue-next';
import { defineProps } from 'vue';

defineProps<{ quiz: IQuiz }>();

function deleteQuiz(id: number) {
    if (confirm('Are you sure you want to delete this quiz?')) {
        // Perform deletion logic here
        console.log(`Quiz with ID ${id} deleted.`);
    }
}
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
                <div class="flex space-x-2">
                    <Button
                        :as="Link"
                        size="sm"
                        variant="ghost"
                        class="absolute top-2 right-2 rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
                        :href="QuizController.edit.url({ quiz: quiz.id })"
                    >
                        <FilePenLine class="h-4 w-4" />
                    </Button>
                    <Button
                        variant="ghost"
                        @click="deleteQuiz(quiz.id)"
                        class="absolute top-2 right-12 rounded-full bg-white p-2 text-destructive shadow-md transition-colors duration-200 hover:bg-gray-100"
                        size="sm"
                    >
                        <Trash class="h-4 w-4" />
                    </Button>
                    <button
                        class="absolute top-2 right-24 rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
                    >
                        <Heart class="h-4 w-4" />
                    </button>
                </div>
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
                    <Badge
                        :class="
                            quiz.status
                                ? 'bg-chart-2 text-destructive-foreground'
                                : 'bg-destructive text-destructive-foreground'
                        "
                    >
                        {{ quiz.status ? 'Active' : 'Inactive' }}
                    </Badge>
                    <p class="py-2 text-sm text-gray-600">
                        expires on : {{ quiz.expire_date?.split(' ')[0] }}
                    </p>
                </div>
                <p class="mb-4 line-clamp-3 text-sm text-gray-600">
                    {{ quiz.description }}
                </p>
            </div>
        </div>
    </div>
</template>
