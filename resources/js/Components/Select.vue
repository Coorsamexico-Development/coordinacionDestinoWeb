<script setup>
import { onMounted, ref } from 'vue';
defineProps({
    modelValue: String | Number,
})

defineEmits(['update:modelValue']);

const select = ref(null);

onMounted(() => {
    if (select.value.hasAttribute('autofocus')) {
        select.value.focus();
    }
});
</script>
<template>
    <select
        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 disabled:bg-gray-300"
        :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="select">
        <slot></slot>
    </select>
</template>
