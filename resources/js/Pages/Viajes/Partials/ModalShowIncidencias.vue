<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       },
       incidencias:Object
   });

   const emit = defineEmits(["close"])

   const close = () => 
   { 
      emit('close');
   };

   const addNewIncidencia = () => 
   {

   }

</script>
<template>
  <DialogModal :maxWidth="'4xl'"  :show="show" @close="close()">
     <template #title>
       <div class="flex flex-row justify-between">
          <h1>Incidencias</h1>
          <span @click="close()">
             Cerrar
          </span>
       </div>
     </template>
     <template #content>
        <table class="w-full" style="font-family: 'Montserrat';">
            <thead>
                <tr>
                    <td class="text-center font-semibold">SKU</td>
                    <td class="text-center font-semibold">Producto</td>
                    <td class="text-center font-semibold">Tipo de incidencia</td>
                    <td class="text-center font-semibold">Cantidad</td>
                    <td class="text-center font-semibold">Evidencias</td>
                    <td class="text-center font-semibold">Reporte POD</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="incidencia in incidencias" :key="incidencia.id">
                    <td class="text-center text-sm py-2 px-4">
                      {{ incidencia.producto }}
                    </td>
                    <td class="text-center py-2 px-4">
                        {{ incidencia.tipo_incidencia }}
                    </td>
                    <td class="text-center py-2 px-4">
                        {{ incidencia.cantidad }}
                    </td>
                    <td class="flex items-center justify-center py-2 px-4">
                      <div v-if="incidencia.evidencias.length > 0">
                        <div v-for=" (evidencia, key) in incidencia.evidencias" :key="evidencia.id">
                          <div >
                               <a v-if="key == 0 "  :href="evidencia.evidencia"  :data-fancybox="'gallery'">
                                <ButtonWatch class="w-8 h-6" :color="'#44BFFC'"  />
                               </a>
                               <a v-else class="hidden" :href="evidencia.evidencia"  :data-fancybox="'gallery'">
                                 <ButtonWatch class="w-8 h-6" :color="'#44BFFC'"  />
                               </a>
                           </div>
                         </div>
                      </div>
                    </td>
                </tr>
            </tbody>
          </table>
          <div class="flex justify-center mt-4">
             <button @click="addNewIncidencia()" class="p-2 px-4 border-2 rounded-full">
               <p class="text-xl font-bold text-red-400">+</p>
            </button>
          </div>
     </template>
   </DialogModal>
</template>