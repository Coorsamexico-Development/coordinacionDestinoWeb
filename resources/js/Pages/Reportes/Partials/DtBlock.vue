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
let status = ref([]);
//Funcion modales
let modalWatch = ref(false);
const modalWatchOpen = () => 
{
  modalWatch.value=true;
  axios.get(route('showHistorico'), {params:{
   id:props.dt.id
  }}).then(response =>
  {
    //console.log(response);
    infoModal.value = response.data.historico;
    status.value = response.data.status;
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
   <div class="flex justify-between m-4 border rounded-lg" :style="{borderColor: dt.color}" >
      <div class="w-4 rounded-s-lg" :style="{backgroundColor:dt.color}">
      </div>
      <div class="px-4 py-2">
        <div class="flex flex-row my-1">
           <h1 class="text-xs font-bold">DT: </h1>
           <p class="text-xs ">{{ dt.referencia_dt }} </p>
         </div>
         <div class="flex flex-row my-1"> 
            <h1 class="text-xs font-bold">Confirmacion: </h1>
            <p class="text-xs ">{{ dt.confirmacion }}</p>
         </div>
         <div class="flex flex-row my-1"> 
            <h1 class="text-xs font-bold">Línea de transporte: </h1>
            <p class="text-xs ">{{ dt.linea_transporte }}</p>
         </div>
      </div>
      <div class="px-2 py-2">
         <div class="ml-8">
           <ButtonWatch @click="modalWatchOpen()" :color="dt.color" />
         </div>
         <div>
            <p class="text-xs ">{{ dt.cita.substring(0,10) }}</p>
         </div>
         <div>
            <p class="text-xs">{{ dt.cita.substring(10,16) }}</p>
         </div>
      </div>
   </div>
   <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
</template>