<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      incidencias:Array
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
});

 const close = () => 
  { 
     emit('close');
  };
</script>
<template>
   <DialogModal maxWidth="4xl"  :show="show" @close="close()">
      <template #title>
         <div class="flex flex-row justify-between">
            <h1>Incidencias</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content>
       
          <table class="w-full">
            <thead>
                <tr>
                    <th>Tipo de incidencia</th>
                    <th>Cantidad</th>
                    <th>Evidencias</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="incidencia in incidencias" :key="incidencia.id">
                   <td class="text-center">
                     {{ incidencia.tipo_incidencia }}
                   </td>
                   <td class="text-center">
                     {{ incidencia.cantidad }}
                   </td>
                   <td class="text-center"> 
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
                   </td >
                </tr>
            </tbody>
          </table>
       </template>
   </DialogModal>
</template>