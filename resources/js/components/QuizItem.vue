<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import type { Quiz } from '@/types';
import { Link } from '@inertiajs/vue3';
import { FilePenLine, Heart, Trash } from 'lucide-vue-next';
import { defineProps } from 'vue';
import Badge from './ui/badge/Badge.vue';
import Button from './ui/button/Button.vue';

defineProps<{ item: Quiz }>();

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
            class="w-full5 min-h-[512px] max-w-sm overflow-hidden rounded-lg bg-white shadow-md"
        >
            <div class="relative">
                <img
                    :src="
                        item.picture
                            ? `storage/${item.picture}`
                            : 'https://github.com/unovue.png'
                    "
                    alt="Product image"
                    class="h-64 w-full object-cover"
                />
                <button
                    class="absolute top-2 right-2 rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
                >
                    <Heart class="h-4 w-4" />
                </button>
            </div>
            <div class="p-4">
                <div class="mb-2 flex items-start justify-between">
                    <div>
                        <h2
                            class="mb-1 line-clamp-1 text-xl font-semibold text-gray-800"
                        >
                            {{ item.title }}
                        </h2>
                    </div>
                </div>
                <div class="py-2">
                    <Badge
                        :class="
                            item.status
                                ? 'bg-chart-2 text-destructive-foreground'
                                : 'bg-destructive text-destructive-foreground'
                        "
                        >{{ item.status ? 'Active' : 'Inactive' }}</Badge
                    >
                    <p class="py-2 text-sm text-gray-600">
                        expires on : {{ item.expire_date?.split(' ')[0] }}
                    </p>
                </div>
                <p class="mb-4 line-clamp-3 text-sm text-gray-600">
                    {{ item.description }}
                </p>
                <div class="flex space-x-2">
                    <Button
                        :as="Link"
                        size="sm"
                        class="flex items-center justify-between gap-1.5 text-sm"
                        :href="QuizController.edit.url({ quiz: item.id })"
                    >
                        <FilePenLine class="h-4 w-4" /> Edit
                    </Button>
                    <Button
                        variant="ghost"
                        @click="deleteQuiz(item.id)"
                        class="text-destructive"
                        size="sm"
                    >
                        <Trash class="h-4 w-4" /> Delete
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
