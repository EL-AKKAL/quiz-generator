<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { Trash } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Avatar from '../ui/avatar/Avatar.vue';
import AvatarImage from '../ui/avatar/AvatarImage.vue';

const page = usePage();

const file = defineModel<Blob | MediaSource | undefined | string | null>();

defineProps({
    name: String,
});

const isActive = ref(false);

function onChange(event: Event) {
    const target = event.target as HTMLInputElement;
    file.value = target.files?.[0];
}

function dragover(event: DragEvent) {
    event.preventDefault();
    isActive.value = true;
}

function dragleave() {
    isActive.value = false;
}

function drop(event: DragEvent) {
    event.preventDefault();
    file.value = event.dataTransfer?.files[0];
    isActive.value = false;
}

const imageUrl = computed((): string | undefined => {
    if (file.value instanceof Blob || file.value instanceof File)
        return URL.createObjectURL(file.value);
    else if (typeof file.value === 'string')
        return `${page.props.app_url}/storage/${file.value}`;
    else return undefined;
});

const imageState = computed(() => {
    if (file.value instanceof Blob || file.value instanceof File) return 'new';
    if (typeof file.value === 'string') return 'existing';
    return 'removed';
});

const showDeleteIcon = ref(false);
</script>

<template>
    <label class="flex cursor-pointer items-center space-x-4" @mouseover="showDeleteIcon = true"
        @mouseleave="showDeleteIcon = false" style="width: 100%">
        <div class="relative !size-16 rounded-full border border-primary bg-primary/15 !p-0 dark:border-primary/10">
            <Avatar v-if="imageUrl" class="size-16" shape="circle">
                <AvatarImage class="h-full w-full" :src="imageUrl" />
            </Avatar>
            <div v-else class="flex h-16 w-16 items-center justify-center">
                <img src="/default-quiz.png" alt="" />
            </div>
            <Trash v-if="file"
                class="absolute top-0 -right-3 size-6 cursor-pointer rounded-full bg-primary-foreground p-1 text-destructive"
                :class="{
                    block: showDeleteIcon,
                    hidden: !showDeleteIcon,
                }" @click.prevent="file = null" @click.stop />
        </div>
        <div class="dark:bg-surface-800 bg-primary-100/30 border-primary-50 dark:border-primary-800/20 mr-0 flex h-full w-full flex-col justify-center rounded-lg border p-2 py-3.5 text-center"
            :class="{
                'box-border border-2 border-dashed': isActive,
            }" @dragover="dragover" @dragleave="dragleave" @drop="drop">
            <p class="mb-1 text-sm">
                <span class="font-medium"> Click to import </span>
                or slide and deposit
            </p>
            <span class="text-muted-color text-xs">
                JPG, JPEG or WEBP (max. 2MB)
            </span>
        </div>
        <input type="file" class="w-0" hidden @change="onChange" accept=".jpg,.jpeg,.webp" id="imageInput"
            :name="name" />
        <input type="hidden" :name="`${name}_state`" :value="imageState" />
    </label>
</template>
