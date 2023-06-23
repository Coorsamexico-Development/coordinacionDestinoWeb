<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex items-center px-4 text-xs font-semibold tracking-widest text-gray-700 uppercase transition duration-150 ease-in-out bg-white rounded-md hover:bg-gray-50 disabled:opacity-25">
        <slot />
    </button>
</template>
