<script setup>
 import InputLabel from '@/Components/InputLabel.vue';
 const props = defineProps({
    campoValor:Object,
  });
</script>
<template>
    <div>
        <InputLabel>
            {{ campoValor.campo }}
        </InputLabel>
        <div v-if="campoValor.tipo_campo">

        </div>
    </div>
</template>