<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';

const { appearance, updateAppearance } = useAppearance();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button size="icon" class="!z-50 h-8 w-8 rounded-full">
                <Sun
                    class="h-4 w-4 scale-100 rotate-0 transition-all duration-300 dark:scale-0"
                />
                <Moon
                    class="absolute h-4 w-4 scale-0 transition-all duration-300 dark:scale-100 dark:rotate-0"
                />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuItem
                @click="updateAppearance('light')"
                :class="{
                    'bg-primary/70': appearance === 'light',
                }"
            >
                Light
            </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem
                @click="updateAppearance('dark')"
                :class="{ 'bg-btn': appearance === 'dark' }"
            >
                Dark
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
