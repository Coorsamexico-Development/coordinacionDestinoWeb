<script setup>
import {ref, watch, computed, reactive } from "vue";
//Props
var props = defineProps({
    confirmaciones:Array,
    ubicacion: Object,
    status:Object
});

console.log(props.confirmaciones)

const Newconfirmaciones = computed(() => 
{
    let confirmacionesTemporales = [];
   for (let index = 0; index < props.confirmaciones.length; index++) 
   {
     const confirmacion = props.confirmaciones[index];
     if(confirmacion.status_padre == props.status.id)
     {
       if(confirmacion.ubicacion_id == props.ubicacion.id)
       {
         confirmacionesTemporales.push(confirmacion)
       }
     }
   }

   return confirmacionesTemporales;
});

</script>
<template>
   <div class="font-semibold">
      {{ Newconfirmaciones.length }}
   </div>
</template>