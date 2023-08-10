<script setup> 
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      viajes:Object
  });

  const close = () => { 
     emit('close');
  };
</script>
<template>
       <DialogModal  :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between">
            <h1>Viajes</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >

          <table class="w-full">
            <thead>
               <tr>
                  <th>
                     Confirmación
                  </th>
                  <th>
                     DT
                  </th>
                  <th>
                     Cita
                  </th>
                  <th>
                     Número de cajas
                  </th>
                  <th>
                     Linea de transporte
                  </th>
                  <th>
                     Plataforma
                  </th>
                  <th>
                     Documento final
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="viaje in viajes" :key="viaje.id">
                  <td>
                     {{ viaje.confirmacion }}
                  </td>
                  <td>
                     {{ viaje.dt }}
                  </td>
                  <td>
                     {{ viaje.cita }}
                  </td>
                  <td>
                     {{ viaje.numero_cajas }}
                  </td>
                  <td>
                     {{ viaje.linea_transporte }}
                  </td>
                  <td>
                     {{ viaje.plataforma }}
                  </td>
                  <td>
                    
                  </td>
               </tr>
            </tbody>
          </table>
       </template>
   </DialogModal>
</template>