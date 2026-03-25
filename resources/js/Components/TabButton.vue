<script setup>
defineProps({
    active: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="mx-0.5 cursor-pointer select-none">
        <div
            v-if="active"
            class="bg-sky-400 px-4 py-1 rounded-full text-white flex flex-row items-center transition-colors"
        >
            <slot name="icon"></slot>
            <span style="font-family: Montserrat"><slot /></span>
        </div>
        <div
            v-else
            class="flex flex-row items-center px-4 py-1 text-[#9B9B9B] hover:text-gray-700 transition-colors"
        >
            <slot name="icon"></slot>
            <span style="font-family: Montserrat"><slot /></span>
        </div>
    </div>
</template>
