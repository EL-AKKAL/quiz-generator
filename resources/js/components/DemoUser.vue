<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const quote = page.props.quote;
</script>

<template>
    <div v-if="quote" class="z-20 mt-auto">
        <blockquote class="!space-y-0">
            <p class="!z-50 text-xs lg:text-lg">
                &ldquo;Use demo user if you dont have an account&rdquo;
            </p>
            <footer class="text-xs lg:text-sm">
                test@example.com : password
            </footer>
        </blockquote>
    </div>
</template>
