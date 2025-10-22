<script setup lang="ts">
import type { Quiz } from '@/types';
import { Link } from '@inertiajs/vue3';
import { FilePenLine, Heart, Trash } from 'lucide-vue-next';
import { defineProps } from 'vue';
import Button from './ui/button/Button.vue';

defineProps<{ item: Quiz }>();
</script>
<template>
    <div>
        <div
            class="w-full5 max-w-sm overflow-hidden rounded-lg bg-white shadow-md"
        >
            <div class="relative">
                <img
                    src="https://github.com/unovue.png"
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
                    <p class="text-lg font-bold text-green-600">
                        {{ item.status }}
                    </p>
                    <p class="text-sm text-gray-600">{{ item.expire_date }}</p>
                </div>
                <p class="mb-4 line-clamp-3 text-sm text-gray-600">
                    {{ item.description }}
                </p>
                <div class="flex space-x-2">
                    <Button
                        :as="Link"
                        size="sm"
                        class="flex items-center justify-between gap-1.5 text-sm"
                        :href="`/quiz/${item.id}`"
                    >
                        <FilePenLine class="h-4 w-4" /> Edit
                    </Button>
                    <Button variant="ghost" class="text-destructive" size="sm">
                        <Trash class="h-4 w-4" /> Delete
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>
