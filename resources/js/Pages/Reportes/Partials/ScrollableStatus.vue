<script setup>
//Importaciones
import {ref, watch, computed } from "vue";
import { Link, useForm } from '@inertiajs/vue3'
import ButtonDropZone from '@/Components/ButtonDropZone.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import UbicacionDesplegable from './UbicacionDesplegable.vue';

//Props
var props = defineProps({
    statu:Object,
    ubicaciones:Object,
    plataformas:Object,
    contadores:Object,
    buscador:String
});

//Formulario para subir excel
const document = ref(null)
const formNewDts = useForm({
  document: null,
})

const contadorIndividual = computed(() => 
{
  return   props.contadores.filter(contador => contador.status_padre == props.statu.id);
});

</script>
<template>
   <div class="flex flex-row justify-between">
      <h1 class="text-lg" style="font-family: 'Montserrat';">{{ statu.nombre }}</h1>
      <div v-if="statu.id == 1">
        <a :href="route('downloadReport')"  class="px-2 border rounded-lg">
           <span style="font-family: 'Montserrat';">Descargar ejemplo</span>
        </a>
      </div>
      <div v-if="statu.id == 1">
           <ButtonDropZone v-model="document" />
        </div>
   </div>
   <div class="grid grid-cols-2 gap-1">
      <div class="flex flex-row items-center justify-between w-full p-2 py-3 m-1 border rounded-lg" v-for="contador in contadorIndividual" :key="contador.id" :style="{backgroundColor:contador.color}">
         <p class="text-sm text-white uppercase">
           {{contador.nombre}}:
         </p>
         <p class="text-3xl font-bold text-white">
            {{ contador.confirmaciones_dts.length }}
         </p>
      </div>
   </div>
</template>
<style>
  ::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 10px #c6c6c6; 
  border-radius: 1px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #C5C5C5; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #C5C5C5; 
}
</style>