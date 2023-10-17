<script setup>
  //Props
 var props = defineProps({
      dt:Object,
  });
</script>
<template>
 <div class="grid grid-cols-12 m-3 border rounded-lg drop-shadow-2xl" >
    <div class="w-4 col-start-1 mr-4 rounded-s-lg" :style="{backgroundColor:dt.color}">
    </div>
    <div class="col-start-2 col-end-7 py-2">
      <div class="flex flex-row my-1">
         <h1 class="text-xl font-semibold uppercase">DT: </h1>
         <p class="text-xl ">{{ dt.referencia_dt }} </p>
      </div>
    </div>
  </div>
</template>