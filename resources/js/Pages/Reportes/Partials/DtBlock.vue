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
   <div class="flex m-4 border rounded-lg drop-shadow-lg" >
      <div class="w-4 mr-4 rounded-s-lg" :style="{backgroundColor:dt.color}">
      </div>
      <div class="py-2">
        <div class="flex flex-row my-1">
           <h1 class="text-xl font-semibold uppercase">DT: </h1>
           <p class="text-xl ">{{ dt.referencia_dt }} </p>
         </div>
         <div class="flex flex-row my-1"> 
            <h1 class="text-sm font-semibold uppercase">Conf: </h1>
            <p class="text-sm ">{{ dt.confirmacion }}</p>
         </div>
         <div class="flex flex-row my-1"> 
            <h1 class="text-sm font-bold uppercase">LT: </h1>
            <p class="text-sm">{{ dt.linea_transporte }}</p>
         </div>
      </div>
      <div class="justify-center px-2 py-2 ml-8">
         <div class="flex flex-row-reverse flex-end">
           <ButtonWatch  @click="modalWatchOpen()" :color="dt.color" />
         </div>
         <div class="flex flex-row-reverse items-center" :style="{color:dt.color}">
            <h1 class="text-sm">{{ dt.status }}</h1>
            <span class="w-2 h-2 mr-2 rounded-full" :style="{backgroundColor:dt.color}"></span>
         </div>
         <div class="flex flex-row justify-between">
            <div>
              <p class="text-xs ">{{ dt.cita.substring(0,10) }}</p>
            </div>
            <div>
               <p class="text-xs">{{ dt.cita.substring(10,16) }}</p>
            </div>
         </div>
      </div>
   </div>
   <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
</template>