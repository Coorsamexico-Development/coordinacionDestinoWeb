<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref, watch, computed, reactive } from "vue";
import { router, Link, useForm  } from '@inertiajs/vue3'
import PaginationAxios from '@/Components/PaginationAxios.vue';
import axios from 'axios';
import ModalWatchHistoricoStatus from '../Reportes/Modals/ModalWatchHistoricoStatus.vue';
import ModalShowOcs from './Partials/ModalShowOcs.vue';
import TextInput from '@/Components/TextInput.vue';
import ButtonWatch from '@/Components/ButtonWatch.vue';
import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import PaginationInertia from '@/Components/PaginationInertia.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

var props = defineProps({
    viajes:Object,
    productos:Object,
    tipos_incidencias:Object
});

//console.log(props.viajes)

Fancybox.bind("[data-fancybox]", {
    // Your custom options
 });

//Infomracion del historico para el modal
let status = ref([]);
let infoModal = ref(null);
let modalWatch = ref(false);
let modalOcs = ref(false);

const buscador = ref('');

watch(buscador, (newBusqueda) => 
{
  router.visit(route('viajes.index'), 
  {
    preserveScroll:true,
    preserveState:true,
    replace:true,
    data:{busqueda:newBusqueda},
    only:['viajes'],
  })
});

const watchHistorico = (viaje) =>
{
  //console.log(viaje)
  modalWatch.value=true;
  axios.get(route('showHistorico'), 
  {params:{
   id:viaje
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

const ocs = ref([]);
const modalWatchClose = () => 
{
  modalWatch.value=false;
}

const viajeActual = ref(-1);
const modalOcsOpen = (id) => 
{
  viajeActual.value = id;
  try 
      {
         axios.get(route('ocsByViaje', {confirmacion_dt_id:id})).then(response => 
          {
             ocs.value = response.data;
             modalOcs.value = true
          })
          .catch(err=> 
          {
              
          })   
      } 
      catch (error) 
      {
          
      }
}

const modalOcsClose = () => 
{
  modalOcs.value = false;
  ocs.value = [];
  viajeActual.value = -1;
}

const reconsultar = (id) => 
{
   try 
      {
         axios.get(route('ocsByViaje', {confirmacion_dt_id:id})).then(response => 
          {
             ocs.value = response.data;
             modalOcs.value = true
          })
          .catch(err=> 
          {
              
          })   
      } 
      catch (error) 
      {
          
      }
}
 
let date = ref(null);
let date2 = ref(null)

</script>
<template>
  <AppLayout title="Viajes">
    <template #header>
       <div class="flex flex-row justify-between mt-2 align-middle" style="font-family: 'Montserrat';">
          <h2 class="mr-4 text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
              Viajes finalizados
          </h2>
          <div class="flex flex-row">
            <VueDatePicker class="mx-2" v-model="date" month-picker vertical placeholder="Selecciona una fecha" />
            <VueDatePicker class="mx-2" v-model="date2" month-picker vertical placeholder="Selecciona una fecha" />
            <div class="mx-2">
              <a v-if="date !== null && date2 !== null" :href="route('descargarReporteViajesConIncidencias', {fechaInicial:date, fechaFinal:date2})">
                <button  class="bg-[#44BFFC] px-8 py-2 rounded-2xl flex flex-row align-middle">
                   <p class="text-white text-sm">
                     Descargar
                   </p>
                   <img  class="w-3 ml-3" src="../../../assets/img/down_arrow.png" />
                </button>
              </a>
              <button disabled class="bg-[#9D9D9D] px-8 py-2 rounded-2xl flex flex-row align-middle" v-else>
                <p class="text-white text-sm">
                     Descargar
                </p>
                <img  class="w-3 ml-3" src="../../../assets/img/down_arrow.png" />
              </button>
            </div>
          </div>
          <div>
            <TextInput v-model="buscador" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar"  />
          </div>
       </div>
    </template>
    <div class="py-4 m-8 bg-white rounded-2xl pb-6" style="font-family: 'Montserrat';">
      <table class="w-full">
        <thead class="border-1 border-sky-500" >
          <tr >
             <th class="font-semibold">Confirmación</th>
             <th class="font-semibold">
                DT
             </th>
             <th class="font-semibold">
                Ubicación
             </th>
             <th class="font-semibold">
                Plataforma
             </th>
             <th class="font-semibold">
                Status final
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
             <th class="font-semibold">POD</th>
             <th class="font-semibold">Documento POD</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="viaje in viajes.data" :key="viaje.id">
             <td class="text-center">{{ viaje.confirmacion }}</td>
             <td class="text-center">{{ viaje.referencia_dt }}</td>
             <td class="text-center">{{ viaje.ubicacion }}</td>
             <td class="text-center">{{ viaje.plataforma }}</td>
             <td class="text-center">{{ viaje.status }}</td>
             <td class="text-center">{{ viaje.cita }}</td>
             <td class="text-center">{{ viaje.numero_cajas }}</td>
             <td class="text-center">
              <button @click="watchHistorico(viaje.id)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                <img class="w-6" src="../../../assets/img/eye.png" />
              </button>
             </td>
             <td class="text-center">
               <button @click="modalOcsOpen(viaje.id)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                <img class="w-6" src="../../../assets/img/eye.png" />
               </button>
             </td>
             <td class="text-center flex justify-center">
                <a :href="viaje.documetoPOD" data-fancybox   data-type="pdf">
                  <button class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                     <img class="w-6" src="../../../assets/img/eye.png" />
                   </button>
                </a>
             </td>
          </tr>
        </tbody>
      </table>
      <PaginationInertia :pagination="viajes" />
    </div>
    <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
    <ModalShowOcs :viaje="viajeActual"  :show="modalOcs" @close="modalOcsClose()" :ocs="ocs" :productos="productos" :tipos_incidencias="tipos_incidencias" @reconsultar="reconsultar" />
  </AppLayout>
</template>