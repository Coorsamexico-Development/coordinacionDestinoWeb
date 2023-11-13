<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import { router, Link, useForm  } from '@inertiajs/vue3'
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import axios from 'axios';
  import ButtonUploadDoc from "./ButtonUploadDoc.vue";
  import ModalShowIncidencias from './ModalShowIncidencias.vue'
  import SpinProgress from "@/Components/SpinProgress.vue";
  import TextInput from '@/Components/TextInput.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import VueDatePicker from '@vuepic/vue-datepicker';
  import '@vuepic/vue-datepicker/dist/main.css';

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

  const inputChange = (e, oc_id, tipo) => 
   {
     //console.log(e);
     //console.log(oc_id);
     //console.log(tipo)
       axios.post(route('saveFacturas',{
        oc_id: oc_id,
        valor: e,
        tipo: tipo
      })).then(response => 
      {
        console.log(response.data)

      })
      .catch(err => 
      {
        console.log(err)
      })
   }

   let selection = ref('ocs');
   const selectTab = (tipo) => 
   {
     selection.value = tipo;
   }

   let fechaDeEnvio = ref();
   let fechaLiberacion = ref();
   let fechaRecepcion = ref();
   let fechaFacturacion = ref();
</script>
<template>
   <DialogModal :maxWidth="'4xl'" :show="show" @close="close()">
     <template #title>
       <div class="flex flex-row justify-between">
          <div @click="selectTab('ocs')">
            <h1>Oc's</h1>
            <span v-if="selection == 'ocs'" class="w-8 h-1 bg-[#44BFFC] absolute"></span>
          </div>
          <div @click="selectTab('fechas')">
             <h1>Fechas</h1>
             <span v-if="selection == 'fechas'" class="w-14 h-1 bg-[#44BFFC] absolute"></span>
          </div>
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
      <div v-if="selection == 'ocs'">
         <div class="overflow-y-auto h-5/6">
            <table class="w-full mt-2">
                <thead class="border-b-2">
                   <tr>
                      <td class="pb-2 font-semibold text-center">Referencia</td>
                      <td class="pb-2 font-semibold text-center">Facturado</td>
                      <td class="pb-2 font-semibold text-center">En POD</td>
                      <td class="pb-2 font-semibold text-center">Incidencias</td>
                      <td class="pb-2 font-semibold text-center">FAC MI</td>
                      <td class="pb-2 font-semibold text-center">FAC SAD</td>
                      <td class="pb-2 font-semibold text-center">Folio recibidor</td>
                   </tr>
                </thead>
                <tbody>
                   <tr v-for="(oc) in ocs" :key="oc.id">
                     <td class="py-2 text-center">{{ oc.referencia }}</td>
                     <td class="py-2 text-center">{{oc.facturado}}</td>
                     <td class="py-2 text-center">{{oc.enPOD}}</td>
                     <td class="py-2 text-center">
                        <div class="flex justify-center" v-if="oc.incidencias.length > 0"> 
                           <ButtonWatch @click="openModalIncidencias(oc)" class="w-8 h-6" :color="'#44BFFC'" />
                        </div>
                     </td>
                     <td class="text-center">
                      <div v-if="oc.FACMI">
                          <TextInput  class="w-8/12" :value="oc.FACMI" @input="inputChange($event.target.value, oc.id , 'FACMI')"  />
                       </div>
                       <div v-else>
                         <TextInput class="w-8/12" @input="inputChange($event.target.value, oc.id, 'FACMI')"  />
                       </div>
                     </td>
                     <td class="text-center">
                      <div v-if="oc.FACSAD">
                          <TextInput class="w-8/12"  :value="oc.FACSAD" @input="inputChange($event.target.value, oc.id, 'FACSAD')"  />
                       </div>
                       <div v-else>
                         <TextInput class="w-8/12" @input="inputChange($event.target.value, oc.id,'FACSAD')"  />
                       </div>
                     </td>
                     <td class="text-center">
                      <div v-if="oc.folio_recibidor">
                          <TextInput class="w-8/12"  :value="oc.folio_recibidor" @input="inputChange($event.target.value, oc.id,'folio_recibidor')"  />
                       </div>
                       <div v-else>
                         <TextInput class="w-8/12" @input="inputChange($event.target.value, oc.id,'folio_recibidor')"  />
                       </div>
                     </td>
                   </tr> 
                </tbody>
             </table>
         </div>
         <div class="flex justify-end mt-2">
            <ButtonUploadDoc  v-model="document" />
            <SpinProgress v-if="formDocumento.processing" :inprogress="formDocumento.processing" />
         </div>
      </div>
      <div v-if="selection == 'fechas'">
          <div class="my-2">
            <InputLabel :value="'Fecha de envio'" />
            <VueDatePicker v-model="fechaDeEnvio" placeholder="Selecciona una fecha de envio" position="left" :teleport="true"  :enable-time-picker="false" />
          </div>
          <div class="my-2">
            <InputLabel :value="'Fecha de liberación de ventanilla'" />
            <VueDatePicker v-model="fechaLiberacion" placeholder="Selecciona una fecha de liberación de ventanilla" position="left" :teleport="true"  :enable-time-picker="false" />
          </div>
          <div class="my-2">
            <InputLabel :value="'Fecha de recepción'" />
            <VueDatePicker v-model="fechaRecepcion" placeholder="Selecciona una fecha de recepción" position="left" :teleport="true"  :enable-time-picker="false" />
          </div>
          <div class="my-2">
            <InputLabel :value="'Fecha de facturación'" />
            <VueDatePicker v-model="fechaFacturacion" placeholder="Selecciona una fecha de facturación" position="left" :teleport="true"  :enable-time-picker="false" />
          </div>
      </div>
     </template>
   </DialogModal>
   <ModalShowIncidencias  :oc="ocToModal" :productos="productos" :show="modalIncidencias" @close="closeModalIncidencias()" :incidencias="incidencias" :tipos_incidencias="tipos_incidencias" @reconsultar="reconsultar" />
</template>