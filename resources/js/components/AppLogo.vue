<script setup lang="ts">
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        :href="home()"
        class="!z-50 flex items-center text-lg font-bold text-primary"
    >
        ELAKKAL AYOUB
    </Link>
</template>
