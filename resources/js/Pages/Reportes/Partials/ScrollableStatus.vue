<script setup>
//Importaciones
import {ref, watch, computed, onUpdated  } from "vue";
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
    buscador:String,
    fecha:String
});

//Formulario para subir excel
const document = ref(null)
const formNewDts = useForm({
  document: null,
});

let fechaToComponent = ref(null);
let buscadorToComponent = ref(null);
onUpdated(() => 
{
  //console.log(props)
  fechaToComponent.value = props.fecha;
  buscadorToComponent.value = props.buscador;
})

//Watcher para la carga del reporte
watch(document, (documentoCargado) => 
{
   formNewDts.document = documentoCargado
   try 
   {
      if(formNewDts.document !== null)
      {
         formNewDts.post(route('reportes.store'),
         {
            onSuccess: () => {
               formNewDts.reset();
               document.value = null;
            },
            onError:(err) => 
            {
              console.log(err);
              formNewDts.reset();
              document.value = null;
            }
         }
         );
      }
   } 
   catch (error) 
   {
     console.log(error)  
   }
});

const contadorIndividual = computed(() => 
{
  return   props.contadores.filter(contador => contador.status_padre == props.statu.id);
});

</script>
<template>
   <div :id="'status-'+statu.id">
      <div class="flex flex-row justify-between my-2">
      <h1 class="text-lg" style="font-family: 'Montserrat';" :id="'status-name-'+statu.id">{{ statu.nombre }}</h1>
      <div v-if="statu.id == 1">
        <a :href="route('downloadReport')" id="downloadReport"  class="px-2 rounded-xl bg-[#697FEA] py-1" >
           <span style="font-family: 'Montserrat';" class="text-white">Descargar ejemplo</span>
        </a>
      </div>
      <div v-if="statu.id == 1">
           <ButtonDropZone id="dropzone" v-model="document" />
        </div>
   </div>
   <div class="grid grid-cols-2 gap-1">
      <div :id="'cotador-'+contador.id" class="flex flex-row items-center justify-between w-full p-2 py-3 m-1 border rounded-lg" v-for="contador in contadorIndividual" :key="contador.id" :style="{backgroundColor:contador.color}">
         <p class="text-sm text-white uppercase">
           {{contador.nombre}}:
         </p>
         <p class="text-4xl text-white" style="font-weight:900">
            {{ contador.confirmaciones_dts.length }}
         </p>
      </div>
   </div>
    <!--body-->
     <div class="px-4 py-4 rounded-lg snap-2" style="overflow-y: scroll;">
         <div v-for="ubicacion in ubicaciones" :key="ubicacion.id">
            <UbicacionDesplegable  :buscador="buscadorToComponent" :ubicacion="ubicacion" :plataformas="plataformas" :status="statu" :fecha="fechaToComponent" />
         </div>
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