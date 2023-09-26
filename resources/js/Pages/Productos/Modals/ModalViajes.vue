<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      producto:Object,
      viajes:Array
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
            <h1>Viajes en los que se ha registrado con incidencia</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
         <table class="w-full">
            <thead>
                <tr>
                    <th>Confirmación</th>
                    <th>No.Cajas</th>
                    <th>Cerrado</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="viaje in viajes" :key="viaje.id">
                  <td>
                     {{ viaje.confirmacion }}
                  </td>
                  <td>
                    {{ viaje.numero_cajas }}
                  </td>
                  <td>
                    {{ viaje.cerrado }}
                  </td>
                </tr>
            </tbody>
         </table>
       </template>
     </DialogModal>
</template>