<script setup>
import {ref, watch, computed, reactive } from "vue";
import ButtonWatch from '@/Components/ButtonWatch.vue'
import ModalWatchHistoricoStatus from '../Modals/ModalWatchHistoricoStatus.vue';
import ModalAddOcs from "../Modals/ModalAddOcs.vue";
import axios from "axios";
  //Props
var props = defineProps({
    dt:Object,
});

let infoModal = ref(null);
let status = ref([]);
//Funcion modales
let modalWatch = ref(false);
/*
const modalWatchOpen = () => 
{
  console.log(props.dt.id)
  modalWatch.value=true;
  axios.get(route('showHistorico'), {params:{
   id:props.dt.id
  }}).then(response =>
  {
    console.log(response);
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

let modalOcs = ref(false);

const modalOcsOpen = () => 
{
  modalOcs.value = true;
  try {
     consultarOcs();
  } 
  catch (error) 
  {
    
  }
}

const modalOcsClose = () => 
{
   modalOcs.value = false;
}

let ocs = ref([]);
const consultarOcs = () => 
{
  try 
    {
        axios.get(route('consultarOcs', {confirmacion:props.dt.confirmacion})).then(response => 
        {
           //console.log(response.data)
           ocs.value = response.data;
        })
        .catch(err=> 
        {
            
        })   
    } 
    catch (error) 
    {
        
    }
}
*/
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
         <div class="flex flex-row my-1"> 
            <h1 class="text-sm font-semibold uppercase">Conf: </h1>
            <p class="text-sm ">{{ dt.confirmacion }}</p>
         </div>
         <div class="flex flex-row my-1"> 
            <h1 class="text-xs font-bold uppercase">LT: </h1>
            <p class="text-xs">{{ dt.linea_transporte }}</p>
         </div>
      </div>
      <div class="justify-center col-start-7 col-end-13 px-2 py-2">
         <div class="flex flex-row-reverse flex-end">
           <ButtonWatch  @click="modalWatchOpen()" :color="dt.color" />
           <button @click="modalOcsOpen()" :style="{backgroundColor:dt.color}" class="flex items-center justify-center px-2 py-1 rounded-full w-9 mx-2" >
            <p class="text-sm text-white">OCS</p>
           </button>
         </div>
         <div class="flex flex-row-reverse items-center" :style="{color:dt.color}">
            <h1 class="text-sm">{{ dt.status }}</h1>
            <span class="w-2 h-2 mr-2 rounded-full" :style="{backgroundColor:dt.color}"></span>
         </div>
         <div class="flex flex-row items-center justify-center mt-1">
            <div class="flex flex-row mr-2">
              <img class="w-3 h-3 mx-1" src="../../../../assets/img/calendario.png" />
              <div>
                <p class="" style="font-size: 0.7rem;">{{ dt.cita.substring(5,7) }} / {{ dt.cita.substring(8,10) }}  </p>
              </div>
            </div>
            <div class="flex flex-row"> 
              <img class="w-3 h-3 mx-1" src="../../../../assets/img/reloj-de-pared.png" />
              <p class="text-xs">{{ dt.cita.substring(10,16) }}</p>
            </div>
         </div>
      </div>
   </div>
       <!--
   <div v-if="infoModal !== null">

     <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
   
   </div>
   <ModalAddOcs :show="modalOcs" @close="modalOcsClose()" :confirmacion="dt.confirmacion" :ocsAxios="ocs" @reconsultar="consultarOcs()" />
     -->
</template>