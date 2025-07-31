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
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" :class="className" v-bind="$attrs">
        <!-- Left Footprint -->
        <ellipse cx="12" cy="20" rx="3" ry="8" fill="currentColor" transform="rotate(-15 12 20)" />
        <!-- Right Footprint -->
        <ellipse cx="28" cy="20" rx="3" ry="8" fill="currentColor" transform="rotate(15 28 20)" />
        <!-- Heart in center -->
        <path
            fill="currentColor"
            d="M20 12c-1.5-2-4-2.5-6-1.5-2 .5-3 2.5-3 4.5 0 3 3 6 9 9 6-3 9-6 9-9 0-2-1-4-3-4.5-2-.5-4.5-.5-6 1.5z"
        />
    </svg>
</template>
