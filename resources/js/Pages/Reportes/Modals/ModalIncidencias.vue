<script setup>
  import { ref, watch } from 'vue';
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
  import '@fancyapps/ui/dist/fancybox/fancybox.css';
  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      }
    ,
    incidencias:Object
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
   });

  const tamañoModal = ref('2xl')
  const close = () => 
  { 
     emit('close');
  };
</script>
<template>
   <DialogModal :maxWidth="tamañoModal"   :show="show" @close="close()">
    <template #title>
         <div class="flex flex-row justify-between" style="font-family: 'Montserrat';">
            <h1>Incidencias</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
          <table class="w-full" style="font-family: 'Montserrat';">
            <thead>
                <tr>
                    <td class="text-center font-semibold">Producto</td>
                    <td class="text-center font-semibold">Tipo de incidencia</td>
                    <td class="text-center font-semibold">Cantidad</td>
                    <td class="text-center font-semibold">Evidencias</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="incidencia in incidencias" :key="incidencia.id">
                    <td class="text-center text-sm">
                      {{ incidencia.producto }}
                    </td>
                    <td class="text-center">
                        {{ incidencia.tipo_incidencia }}
                    </td>
                    <td class="text-center">
                        {{ incidencia.cantidad }}
                    </td>
                    <td class="flex items-center justify-center">
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
       </template>
   </DialogModal>
</template>