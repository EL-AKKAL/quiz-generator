<script setup lang="ts">
import { defineProps } from 'vue';
import type { Quiz } from '@/types';
import {  Link } from '@inertiajs/vue3';


const props = defineProps<{
  item: Quiz;
}>();

</script>
<template>
    <div>
        <h2>{{ item.title }}</h2>
        <p>{{ item.description }}</p>
        <!-- <Link :href="{ name: 'quizzes.show', params: { quiz: item.id } }">View Quiz</Link> -->
    </div>
</template>


