<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref, watch, computed, reactive } from "vue";
import { router, Link, useForm  } from '@inertiajs/vue3'
import PaginationAxios from '@/Components/PaginationAxios.vue';
import axios from 'axios';
import ModalWatchHistoricoStatus from '../Reportes/Modals/ModalWatchHistoricoStatus.vue';

var props = defineProps({
    viajes:Object,
});

const viajesData = ref(props.viajes.data);
const viajesChange = ref(props.viajes);
//Infomracion del historico para el modal
let status = ref([]);
let infoModal = ref(null);
let modalWatch = ref(false);

const loadPage = (page) => 
{
    axios.get(page)
    .then(response => 
    {
      viajesData.value = response.data.data; //seteamos la info
      viajesChange.value = response.data;
    })
    .catch(e => {
        // Podemos mostrar los errores en la consola
        console.log(e);
    })
}


const watchHistorico = (viaje) =>
{
  console.log(viaje)
  modalWatch.value=true;
  axios.get(route('showHistorico'), 
  {params:{
   id:viaje.id
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
 
</script>
<template>
  <AppLayout title="Viajes">
    <template #header>
       <div class="flex flex-row mt-2 align-middle" style="font-family: 'Montserrat';">
          <h2 class="mr-4 text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
              Viajes finalizados
          </h2>
       </div>
    </template>
    <div class="py-4 m-8 bg-white rounded-2xl" style="font-family: 'Montserrat';">
      <table class="w-full">
        <thead  >
          <tr class="border-1 border-sky-500">
             <th class="font-semibold">Confirmación</th>
             <th class="font-semibold">
                DT
             </th>
             <th class="font-semibold">
              <div class="flex flex-row justify-center align-middle">
                <img class="w-6 h-6 mr-2" src="../../../assets/img/calendario_blue.png"/>
                <p>Cita</p>
              </div>
            </th>
             <th class="font-semibold">
              <div class="flex flex-row justify-center align-middle">
                <img class="w-6 h-6 mr-2" src="../../../assets/img/cajas.png"/>
                <p>NO. Cajas</p>
              </div>
            </th>
             <th class="font-semibold">Historial</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="viaje in viajesData" :key="viaje.id">
             <td class="text-center">{{ viaje.confirmacion }}</td>
             <td class="text-center">{{ viaje.dt }}</td>
             <td class="text-center">{{ viaje.cita }}</td>
             <td class="text-center">{{ viaje.numero_cajas }}</td>
             <td class="text-center">
              <button @click="watchHistorico(viaje.id)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                <img class="w-6" src="../../../assets/img/eye.png" />
              </button>
             </td>
          </tr>
        </tbody>
      </table>
      <PaginationAxios :pagination="viajesChange" @loadPage="loadPage($event)" />
    </div>
    <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
  </AppLayout>
</template>