<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: String,
    required:Boolean
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        :required="required"
        ref="input"
        class="border shadow-sm focus:border-indigo-500 focus:ring-indigo-500 rounded-full py-1 text-base"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
</template>
