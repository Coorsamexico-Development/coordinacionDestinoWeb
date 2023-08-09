<script setup>
import { computed } from "vue";

const props = defineProps({
    month: {
        type: Number,
    },
    currentMonth: {
        type: Number,
    },
});

const active = computed(() => {
    return props.month === props.currentMonth;
});
</script>

<template>
    <div
        class="px-2 py-1 text-xs text-center rounded-lg hover:cursor-pointer"
        :class="{ 'bg-aqua-500 text-[#44BFFC]': active }"
    >
        <slot />
    </div>
</template>
<style scoped></style>
