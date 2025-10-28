<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    unit?: string;
}>();

const message = computed(() =>
    props.unit ? `No ${props.unit} added yet.` : 'No items added yet.',
);
</script>
<template>
    <div
        class="col-span-2 mt-4 w-full rounded-sm border-2 border-dashed p-4 text-center text-gray-600"
    >
        {{ message }}
    </div>
</template>
