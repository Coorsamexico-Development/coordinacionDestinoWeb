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
       <div  class="bg-white drop-shadow-lg my-4 mx-2 p-4 rounded-lg" v-for="oc in ocs" :key="oc.id">
          <div  @click="mostrar(oc)" class="flex justify-between py-1">
             <h1 class="text-lg font-semibold">{{ oc.referencia }}</h1>
             <div>
                <svg  v-if="showThings" class="mx-2" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                  <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
                </svg>    
                <svg  v-if="!showThings" class="mx-2 rotate-180" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                  <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
                </svg>
             </div>
          </div>
          <div class="" >
             <Transition name="slide-fade" class="mt-2 border-t-2">
              <div v-if="showThings && (oc.id == ocActual.id)" >
                 <table class="w-full mt-2">
                    <thead>
                       <tr>
                          <td class="text-center">Facturado</td>
                          <td class="text-center">En POD</td>
                          <td class="text-center">Incidencias</td>
                       </tr>
                    </thead>
                    <tbody>
                       <tr>
                          <td class="text-center">{{oc.facturado}}</td>
                          <td class="text-center">{{oc.enPOD}}</td>
                          <td class="flex justify-center">
                             <ButtonWatch @click="openModalIncidencias(oc)" class="w-8 h-6" :color="'#44BFFC'" />
                          </td>
                       </tr> 
                    </tbody>
                 </table>
              </div>
            </Transition>
          </div>
       </div>
      </div>
      <div class="flex justify-end">
         <ButtonUploadDoc v-model="document" />
      </div>
     </template>
   </DialogModal>
   <ModalShowIncidencias :show="modalIncidencias" @close="closeModalIncidencias()" :incidencias="incidencias" />
</template>