<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 200 200"
        :class="className"
        v-bind="$attrs"
        fill="none"
    >
        <defs>
            <linearGradient id="a" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop
                    offset="0%"
                    style="stop-color: #a78bfa; stop-opacity: 1"
                />
                <stop
                    offset="100%"
                    style="stop-color: #7c3aed; stop-opacity: 1"
                />
            </linearGradient>
            <linearGradient id="b" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop
                    offset="0%"
                    style="stop-color: #8b5cf6; stop-opacity: 1"
                />
                <stop
                    offset="100%"
                    style="stop-color: #6d28d9; stop-opacity: 1"
                />
            </linearGradient>
        </defs>
        <circle
            cx="100"
            cy="100"
            r="95"
            class="!fill-current"
            stroke="url(#a)"
            stroke-width="2"
        />
        <circle cx="100" cy="100" r="85" stroke="url(#a)" opacity=".3" />
        <circle
            cx="100"
            cy="100"
            r="75"
            stroke="url(#a)"
            stroke-width=".5"
            opacity=".2"
        />
        <circle cx="100" cy="100" r="55" fill="url(#a)" opacity=".15" />
        <circle cx="100" cy="100" r="55" stroke="url(#a)" stroke-width="2" />
        <circle cy="18" r="3" fill="url(#b)" transform="translate(100 100)" />
        <path
            d="m150 60 10 10h-10ZM50 140l-10-10h10Z"
            fill="url(#a)"
            opacity=".6"
        />
        <text
            x="100"
            y="100"
            font-family="verdana"
            font-size="20"
            font-weight="600"
            fill="#7c3aed"
            text-anchor="middle"
        >
            QUIZGEN
        </text>
        <g stroke="url(#a)" stroke-width=".5" opacity=".1">
            <path d="M70 100h60m-30-30v60" />
            <circle cx="100" cy="100" r="30" />
        </g>
    </svg>
</template>
