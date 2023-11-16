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
import '@vuepic/vue-datepicker/dist/main.css';
import { pickBy } from "lodash";

var props = defineProps({
    viajes:Object,
    productos:Object,
    tipos_incidencias:Object,
    filters: Object,
    status_pod:Object
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

const params = reactive({
    busqueda: props.filters.busqueda,
    fechaInicial: props.filters.fechaInicial,
    fechaFinal: props.filters.fechaFinal,
    fields: props.filters.fields,
    searchs:{
        'confirmacion_dts.confirmacion': '',
        'dts.referencia_dt': '',
        'status.nombre': '',
        'ubicaciones.nombre_ubicacion': '',
        'plataformas.nombre': '',
        ...props.filters.searchs
    },
});


watch(params, () => 
{
  const clearParams = pickBy({ ...params });
  console.log(clearParams)
  
  router.visit(route('viajes.index'), 
  {
    preserveScroll:true,
    preserveState:true,
    replace:true,
    data:clearParams,
    only:['viajes'],
  })
  
});

const dtActual = ref(null);
const watchHistorico = (viaje, dt) =>
{
  dtActual.value = dt;
  console.log(dtActual);
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
  consultarFechasYStatusPOD();
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
 
const sort = (field) => 
{
  if (params.fields === null) 
    {
        params.fields = {};// para que no falle hasOwnProperty
    }
    if (params.fields.hasOwnProperty(field)) {
        params.fields[field] = params.fields[field] === 'asc' ? 'desc' : 'asc';
    } else {
        params.fields[field] = 'asc';
    }
}

let statusPOD = ref(0);
let fechasPOD = ref([]);

const consultarFechasYStatusPOD = () => 
{
  try 
      {
         axios.get(route('consultarFechasStatusPOD', {confirmacion:viajeActual.value})).
         then(response => 
         {
           //console.log(response);
           fechasPOD.value = response.data.fechasPOD;
         }).catch(err => 
         {
            console.log(err)
         })
      } catch (error) 
      {
         
      }
}

</script>
<template>
  <AppLayout title="Viajes">
    <template #header>
       <div class="flex flex-row justify-between mt-2 align-middle" style="font-family: 'Montserrat';">
          <h2 class="mr-4 text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
              Viajes finalizados
          </h2>
          <div class="flex flex-row">
            <VueDatePicker class="mx-2" v-model="params.fechaInicial" month-picker vertical placeholder="Selecciona una fecha" />
            <VueDatePicker class="mx-2" v-model="params.fechaFinal" month-picker vertical placeholder="Selecciona una fecha" />
            <div class="mx-2">
              <a v-if="params.fechaInicial !== null && params.fechaFinal !== null" :href="route('descargarReporteViajesConIncidencias', {fechaInicial:params.fechaInicial, fechaFinal:params.fechaFinal, searchs:params.searchs})">
                <button  class="bg-[#44BFFC] px-8 py-2 rounded-2xl flex flex-row align-middle">
                   <p class="text-sm text-white">
                     Descargar
                   </p>
                   <img  class="w-3 ml-3" src="../../../assets/img/down_arrow.png" />
                </button>
              </a>
              <button disabled class="bg-[#9D9D9D] px-8 py-2 rounded-2xl flex flex-row align-middle" v-else>
                <p class="text-sm text-white">
                     Descargar
                </p>
                <img  class="w-3 ml-3" src="../../../assets/img/down_arrow.png" />
              </button>
            </div>
          </div>
          <div>
            <TextInput v-model="params.busqueda" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar"  />
          </div>
       </div>
    </template>
    <div class="py-4 pb-6 m-8 bg-white rounded-2xl" style="font-family: 'Montserrat';">
      <table class="w-full">
        <thead class="border-1 border-sky-500" >
          <tr >
             <th  class="font-semibold">
              <span class="block my-1" @click="sort('confirmacion')">
                Confirmación
                <template v-if="params.fields && params.fields['confirmacion']">
                     <svg v-if="params.fields['confirmacion'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                         class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                     </svg>
                     <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                         fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                     </svg>
                 </template>
              </span>
              <TextInput class="w-24" v-model="params.searchs['confirmacion_dts.confirmacion']"  />
            </th>
             <th  class="font-semibold">
              <span class="block my-1" @click="sort('referencia_dt')">
                 DT
                 <template v-if="params.fields && params.fields['referencia_dt']">
                     <svg v-if="params.fields['referencia_dt'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                         class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                     </svg>
                     <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                         fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                     </svg>
                 </template>
              </span>
              <TextInput class="w-24" v-model="params.searchs['dts.referencia_dt']"  />
             </th>
             <th  class="font-semibold">
              <span class="block my-1" @click="sort('ubicacion')">
                Ubicación
                <template v-if="params.fields && params.fields['ubicacion']">
                     <svg v-if="params.fields['ubicacion'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                         class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                     </svg>
                     <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                         fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                     </svg>
                 </template>
              </span>
              <TextInput class="w-24" v-model="params.searchs['ubicaciones.nombre_ubicacion']"  />
             </th>
             <th  class="font-semibold">
              <span class="block my-1" @click="sort('plataforma')">
                Plataforma
                <template v-if="params.fields && params.fields['plataforma']">
                     <svg v-if="params.fields['plataforma'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                         class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                     </svg>
                     <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                         fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                     </svg>
                 </template>
              </span>
              <TextInput class="w-24" v-model="params.searchs['plataformas.nombre']"  />
             </th>
             <th  class="font-semibold">
              <span class="block my-1" @click="sort('status')">
                Status final
                <template v-if="params.fields && params.fields['status']">
                     <svg v-if="params.fields['status'] === 'asc'" xmlns="http://www.w3.org/2000/svg"
                         class="inline w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
                     </svg>
                     <svg v-else xmlns="http://www.w3.org/2000/svg" class="inline w-4 h-4" viewBox="0 0 20 20"
                         fill="currentColor">
                         <path
                             d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
                     </svg>
                 </template>
              </span>
              <TextInput class="w-30" v-model="params.searchs['status.nombre']"  />
             </th>
             <th @click="sort('cita')" class="font-semibold">
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
             <th class="font-semibold">Status POD</th>
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
              <button @click="watchHistorico(viaje.id, viaje)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                <img class="w-6" src="../../../assets/img/eye.png" />
              </button>
             </td>
             <td class="text-center">
               <button @click="modalOcsOpen(viaje.id)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                <img class="w-6" src="../../../assets/img/eye.png" />
               </button>
             </td>
             <td class="flex justify-center text-center">
                <a :href="viaje.documetoPOD" data-fancybox   data-type="pdf">
                  <button class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                     <img class="w-6" src="../../../assets/img/eye.png" />
                   </button>
                </a>
             </td>
             <td class="text-center">
              <div v-if="viaje.confirmacion_status_pods.length > 0">
                {{ viaje.confirmacion_status_pods[0].statusPOD }}
              </div>
             </td>
          </tr>
        </tbody>
      </table>
      <PaginationInertia :pagination="viajes" />
    </div>
    <ModalShowOcs :status_pod="status_pod" 
    :viaje="viajeActual"  
    :show="modalOcs"
    @close="modalOcsClose()" 
    :ocs="ocs" 
    :productos="productos" 
    :tipos_incidencias="tipos_incidencias" 
    @reconsultar="reconsultar" 
    :fechasPOD="fechasPOD"
    />
    <div v-if="dtActual !== null"> 
      <ModalWatchHistoricoStatus :show="modalWatch" :dt="dtActual" @close="modalWatchClose()" :infoModal="infoModal" :status="status" />
    </div>
 </AppLayout>
</template>