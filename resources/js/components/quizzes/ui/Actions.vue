<script setup lang="ts">
import QuizController from '@/actions/App/Http/Controllers/QuizController';
import Button from '@/components/ui/button/Button.vue';
import { Link } from '@inertiajs/vue3';
import { FilePenLine, Trash } from 'lucide-vue-next';

defineProps<{ id: number }>();

function deleteQuiz(id: number) {
    if (confirm('Are you sure you want to delete this quiz?'))
        console.log(`Quiz with ID ${id} deleted.`);
}
</script>
<template>
    <div class="absolute top-2 right-2 flex space-x-2">
        <Button
            :as="Link"
            size="sm"
            variant="ghost"
            class="rounded-full bg-white p-2 shadow-md transition-colors duration-200 hover:bg-gray-100"
            :href="QuizController.edit.url({ quiz: id })"
        >
            <FilePenLine class="h-4 w-4" />
        </Button>
        <Button
            variant="ghost"
            @click="deleteQuiz(id)"
            class="rounded-full bg-white p-2 text-destructive shadow-md transition-colors duration-200 hover:bg-gray-100"
            size="sm"
        >
            <Trash class="h-4 w-4" />
        </Button>
    </div>
</template>
