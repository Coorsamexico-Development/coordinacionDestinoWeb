<script setup>
var props = defineProps({
  plataforma:Object
});
</script>
<template>
    <div class="flex flex-col items-center">
      <h1 class="text-4xl font-semibold" style="font-family: 'Montserrat';">
        {{ plataforma.confirmaciones_dts.length }}
      </h1>
      <h1 class="text-sm uppercase" style="font-family: 'Montserrat';">
        {{ plataforma.clave }}
      </h1>
    </div>
</template>