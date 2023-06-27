<script setup>
import {ref, watch, computed, reactive } from "vue";
import ButtonWatch from '@/Components/ButtonWatch.vue'
import ModalWatchHistoricoStatus from '../Modals/ModalWatchHistoricoStatus.vue'
import axios from "axios";
  //Props
var props = defineProps({
    dt:Object,
});

let infoModal = ref(null);
//Funcion modales
let modalWatch = ref(false);
const modalWatchOpen = () => 
{
  modalWatch.value=true;
  axios.get(route('showHistorico')).then(response =>
  {
   console.log(response);
  }).catch(err => 
  {
   console.log(err)
  })
}
const modalWatchClose = () => 
{
  modalWatch.value=false;
}

</script>
<template>
   <div class="flex justify-between p-2 m-4 border rounded-lg" :style="{borderColor: dt.color}" >
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
         <div class="ml-8">
           <ButtonWatch @click="modalWatchOpen()" :color="dt.color" />
         </div>
         <div>
            <p class="text-xs">{{ dt.cita.substring(0,10) }}</p>
         </div>
         <div>
            <p class="text-xs">{{ dt.cita.substring(10,16) }}</p>
         </div>
      </div>
   </div>
   <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()"  />
</template>