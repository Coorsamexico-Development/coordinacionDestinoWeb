<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import { router, Link, useForm  } from '@inertiajs/vue3'
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import axios from 'axios';
  import ButtonUploadDoc from "./ButtonUploadDoc.vue";
  import ModalShowIncidencias from './ModalShowIncidencias.vue'

  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       },
       ocs:Object,
       viaje:Number,
       productos:Object,
       tipos_incidencias:Object
   });

   const emit = defineEmits(["close", "reconsultar"])

   const close = () => 
   { 
      emit('close');
   };

   
  let showThings = ref(false)
  let ocActual = ref(-1);
  const mostrar = (oc) => 
  {
    ocActual.value = oc
    showThings.value = !showThings.value
  }

  //Form
  const formDocumento = useForm({
    confirmacion:-1,
    document: null,
  })
  let document = ref(null);

  //Watcher para la carga del reporte
watch(document, (documentoCargado) => 
{
   formDocumento.document = documentoCargado
   formDocumento.confirmacion = props.viaje;
   try 
   {
      if(formDocumento.document !== null)
      {
         //console.log(formDocumento)
         formDocumento.post(route('saveDocPOD'),
         {
            onSuccess: () => 
            {
               formDocumento.reset();
               document.value = null;
               router.visit(route('viajes.index'), 
               {
                 preserveScroll:true,
                 preserveState:true,
                 replace:true,
                 only:['viajes'],
               })
            },
            onError:(err) => 
            {
              console.log(err);
              formDocumento.reset();
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

  let modalIncidencias = ref(false);
  let incidencias = ref([]);
  let ocToModal = ref(null);

  const openModalIncidencias = (oc) => 
  {
    ocToModal.value = oc;
    consultarIncidencias(oc)
    modalIncidencias.value = true;
  }

  const closeModalIncidencias = () => 
  {
    modalIncidencias.value = false;
    incidencias.value = [];
  }

  const consultarIncidencias = (oc) => 
  {
     axios.get(route('getIncidenciasByOc',{
      oc_id:oc.id
     })).then(response => {
       console.log(response.data)
       incidencias.value = response.data
     }).catch(err=>{
      console.log(err)
     })
  }

  const reconsultar = () => 
  {
     emit('reconsultar', props.viaje)
     openModalIncidencias(ocToModal.value)
  }

</script>
<template>
   <DialogModal  :show="show" @close="close()">
     <template #title>
       <div class="flex flex-row justify-between">
          <h1>Oc's</h1>
          <a class="flex justify-center" :href="route('downloadIncidenciasReport', {viaje:viaje})">
             <button  class="bg-[#44BFFC] px-8 py-2 rounded-2xl ">
               <img class="w-3" src="../../../../assets/img/down_arrow.png" />
                   <!--
                    <a :href="route('donwloadExportExample')" class="text-white">
                       Descargar ejemplo
                   </a>
                   -->
             </button>
          </a>
          <span @click="close()">
             Cerrar
          </span>
       </div>
     </template>
     <template #content>
      <div class="overflow-y-auto h-5/6">
         <table class="w-full mt-2">
             <thead class="border-b-2">
                <tr>
                   <td class="text-center font-semibold pb-2">Referencia</td>
                   <td class="text-center font-semibold pb-2">Facturado</td>
                   <td class="text-center font-semibold pb-2">En POD</td>
                   <td class="text-center font-semibold pb-2">Incidencias</td>
                </tr>
             </thead>
             <tbody>
                <tr v-for="oc in ocs" :key="oc.id">
                  <td class="text-center py-2">{{ oc.referencia }}</td>
                  <td class="text-center py-2">{{oc.facturado}}</td>
                  <td class="text-center py-2">{{oc.enPOD}}</td>
                  <td class="text-center py-2">
                     <div class="flex justify-center" v-if="oc.incidencias.length > 0"> 
                        <ButtonWatch @click="openModalIncidencias(oc)" class="w-8 h-6" :color="'#44BFFC'" />
                     </div>
                  </td>
                </tr> 
             </tbody>
          </table>
      </div>
      <div class="flex justify-end mt-2">
         <ButtonUploadDoc v-model="document" />
      </div>
     </template>
   </DialogModal>
   <ModalShowIncidencias  :oc="ocToModal" :productos="productos" :show="modalIncidencias" @close="closeModalIncidencias()" :incidencias="incidencias" :tipos_incidencias="tipos_incidencias" @reconsultar="reconsultar" />
</template>