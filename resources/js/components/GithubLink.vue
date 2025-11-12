<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { Github } from 'lucide-vue-next';
</script>

<template>
    <Button
        as="a"
        href="https://github.com/EL-AKKAL/quiz-generator"
        target="_blank"
        class="!z-50 h-8 w-8 rounded-full !text-primary"
        variant="outline"
    >
        <Github />
    </Button>
</template>
