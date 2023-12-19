<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import ButtonWatch from '@/Components/ButtonWatch.vue'
  import ModalWatchHistoricoStatus from '../Modals/ModalWatchHistoricoStatus.vue';
  import ModalAddOcs from "../Partials/ModalAddOcs.vue";
  import ModalConfirmacionDelete from "../Modals/ModalConfirmacionDelete.vue"
  import axios from "axios";
  import { router } from '@inertiajs/vue3'
    //Props
  var props = defineProps({
      dt:Object,
  });
  
  let infoModal = ref(null);
  let status = ref([]);
  //Funcion modales
  let modalWatch = ref(false);
  
  let viaje = ref(null);
  const modalWatchOpen = (dt_id) => 
  {
    viaje.value = dt_id;
    //console.log(props.dt.id)
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
    viaje.value = null;
  }
  
  let modalOcs = ref(false);
  let ocs = ref([]);
 
  const modalOcsOpen = () => 
  {
    modalOcs.value = true;
    consultarOcs();
  }

  const modalOcsClose = () => 
  {
     modalOcs.value = false;
  }
  

  const consultarOcs =  () => 
  {
    try 
      {
         axios.get(route('consultarOcs', {confirmacion:props.dt.confirmacion})).then(response => 
          {
             console.log(response.data)
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

  const reVisit = (viajeAConsultar) => 
  {
    router.visit(route('reportes.index'), 
     {
       preserveScroll:true,
       preserveState:true,
       replace:true,
       only:['contadores','ubicaciones']
     })

    axios.get(route('showHistorico'), 
    {params:{
     id:viajeAConsultar
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

  const modalDeleteViaje = ref(false);
  const dtAEliminar = ref(null)
  const eliminarViaje = (dt) => 
  {   
     openModalConfirmDeleteViaje();
     dtAEliminar.value = dt;
  }

  const openModalConfirmDeleteViaje = () => 
  {
    modalDeleteViaje.value = true;
  }

  const closeModalConfirmDeleteViaje = () => 
  {
    modalDeleteViaje.value = false;
    dtAEliminar.value = null;
  }

  const confirmacionEliminarViaje = async () =>  //confirmacion de eliminacion de viaje
  {
     //console.log(dtAEliminar.value)
     //console.log('listo para eliminar')
     await axios.post(route('deleteViaje'), 
    {params:
      {
       dt:dtAEliminar.value
      }}).then(response =>
      {
        // console.log(response);
        router.visit(route('reportes.index'),
        {
        preserveScroll:true,
        preserveState:true,
        only:['contadores','ubicaciones', 'plataformas']
       });  
       
       closeModalConfirmDeleteViaje()

      }).catch(err => 
      {
       console.log(err)
      });
  }
  
</script>
<template>
 <div class="grid grid-cols-12 m-3 border rounded-lg" style="  box-shadow:0.3em 0.3em 1em rgba(0, 0, 0, 0.3);">
    <div class="w-4 col-start-1 mr-4 rounded-s-lg" :style="{backgroundColor:dt.color}">
    </div>
    <div class="col-start-2 col-end-7 py-2">
      <div class="flex flex-row my-1" id="dt">
         <h1 class="text-xl font-semibold uppercase">DT: </h1>
         <p class="text-xl ">{{ dt.referencia_dt }} </p>
       </div>
       <div class="flex flex-row my-1" id="confirmacion"> 
          <h1 class="text-sm font-semibold uppercase" style="letter-spacing: 2px;">Conf: </h1>
          <p class="text-sm ">{{ dt.confirmacion }}</p>
       </div>
       <div class="flex flex-row my-1" id="linea-transporte"> 
          <h1 class="text-xs font-bold uppercase text-[#9B9B9B]" >LT: </h1>
          <p class="text-xs text-[#9B9B9B]">{{ dt.linea_transporte }}</p>
       </div>
    </div>
    <div class="justify-center col-start-7 col-end-13 px-2 py-2">
      <div class="flex flex-row-reverse flex-end">
        <ButtonWatch class=""  @click="modalWatchOpen(dt.id)"  :color="dt.color" id="botonHistorico" />
        <button id="botonOcs" @click="modalOcsOpen()" :style="{backgroundColor:dt.color}" class="flex items-center justify-center px-3 py-1 mx-2 rounded-full w-9" >
              <p class="text-sm text-white">Oc's</p>
        </button>
      </div>
      <div class="flex flex-row-reverse items-center mt-2" :style="{color:dt.color}">
         <h1 class="text-sm">{{ dt.status }}</h1>
         <span class="w-2 h-2 mr-2 rounded-full" :style="{backgroundColor:dt.color}"></span>
      </div>
      <div class="flex flex-row items-center justify-center mt-2 ml-20">
         <div class="flex flex-row mr-2" id="fecha-cita">
           <img class="w-3 h-3 mx-1" src="../../../../assets/img/calendario.png" />
           <div>
             <p class="text-[#9B9B9B]" style="font-size: 0.7rem;">{{ dt.cita.substring(5,7) }} / {{ dt.cita.substring(8,10) }}  </p>
           </div>
         </div>
         <div class="flex flex-row" id="fecha-hora"> 
           <img class="w-3 h-3 mx-1" src="../../../../assets/img/reloj-de-pared.png" />
           <p class="text-xs text-[#9B9B9B]">{{ dt.cita.substring(10,16) }}</p>
         </div>
      </div>
      <div class="float-right mt-2 mr-4">
          <button v-if="dt.status_id == 4 || dt.status_id == 5" class="p-1 bg-red-400 rounded-full" @click="eliminarViaje(dt)">
            <img class="w-4 h-5" src="../../../../assets/img/eliminar.png" />
          </button>
      </div>
    </div>
  </div>
  <div v-if="infoModal !== null">
     <ModalWatchHistoricoStatus :show="modalWatch" @close="modalWatchClose()" :infoModal="infoModal" :status="status" :viaje="viaje" :dt="dt" @reVisit="reVisit" />
  </div>
  <ModalAddOcs :show="modalOcs" @close="modalOcsClose()" @reconsultar="consultarOcs()" :ocsAxios="ocs" :confirmacion="dt.confirmacion" />
  <ModalConfirmacionDelete :show="modalDeleteViaje" @close="closeModalConfirmDeleteViaje()" @eliminaViaje="confirmacionEliminarViaje()" />
</template>