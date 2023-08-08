<script setup>
var props = defineProps({
  plataforma:Object
});
</script>
<template>
    <div>
      {{ plataforma.clave }}
    </div>
</template>