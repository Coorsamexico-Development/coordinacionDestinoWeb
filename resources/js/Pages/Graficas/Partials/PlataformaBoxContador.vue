<script setup>
var props = defineProps({
  plataforma:Object
});
</script>
<template>
    <div class="">
      <h1>
        {{ plataforma.clave }}
      </h1>
      <h1>
        {{ plataforma.confirmaciones_dts.length }}
      </h1>
    </div>
</template>