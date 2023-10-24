<script setup>
  import {ref, watch, computed, reactive } from "vue";
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
       ocs:Object

   });

   const emit = defineEmits(["close"])

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

  const document = ref(null) 

  const modalIncidencias = ref(false);
  const incidencias = ref([]);
  const openModalIncidencias = (oc) => 
  {
    incidencias.value = oc.incidencias;
    modalIncidencias.value = true;
  }

  const closeModalIncidencias = () => 
  {
    modalIncidencias.value = false;
  }

</script>
<template>
   <DialogModal  :show="show" @close="close()">
     <template #title>
       <div class="flex flex-row justify-between">
          <h1>Oc's</h1>
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
      <div class="flex justify-end">
         <ButtonUploadDoc v-model="document" />
      </div>
     </template>
   </DialogModal>
   <ModalShowIncidencias :show="modalIncidencias" @close="closeModalIncidencias()" :incidencias="incidencias" />
</template>