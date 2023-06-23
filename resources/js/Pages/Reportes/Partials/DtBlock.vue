<script setup>
import {ref, watch, computed, reactive } from "vue";
  //Props
var props = defineProps({
    dt:Object,
});

const setBgColor = computed(() => {
    return  'bg-['+props.dt.color+ ']';
});

</script>
<template>
   <div class="flex justify-between p-2 m-4 border rounded-lg" :class="setBgColor">
      <div>
        <div class="flex flex-row">
           <h1 class="text-sm font-bold">DT: </h1>
           <p class="text-sm">{{ dt.referencia_dt }} </p>
         </div>
         <div class="flex flex-row"> 
            <h1 class="text-sm font-bold">Confirmacion: </h1>
            <p class="text-sm">{{ dt.confirmacion }}</p>
         </div>
         <div class="flex flex-row"> 
            <h1 class="text-sm font-bold">Línea de transporte: </h1>
            <p class="text-sm">{{ dt.linea_transporte }}</p>
         </div>
      </div>
      <div>
         <div>
           
         </div>
         <div>
            <p class="text-xs">{{ dt.cita.substring(0,10) }}</p>
         </div>
         <div>
            <p class="text-xs">{{ dt.cita.substring(10,16) }}</p>
         </div>
      </div>
   </div>
</template>