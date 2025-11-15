<script setup lang="ts">
import Avatar from '@/components/ui/avatar/Avatar.vue';
import AvatarFallback from '@/components/ui/avatar/AvatarFallback.vue';
import AvatarImage from '@/components/ui/avatar/AvatarImage.vue';
import { computed } from 'vue';

const props = defineProps<{
    picture: string | undefined;
    title: string | undefined;
}>();

const computedPicture = computed(() =>
    props.picture
        ? `/storage/${props.picture}`
        : 'https://github.com/unovue.png',
);
</script>
<template>
    <Avatar class="h-20 w-20">
        <AvatarImage :src="computedPicture" :alt="title ?? ''" />
        <AvatarFallback>Not found</AvatarFallback>
    </Avatar>
</template>
