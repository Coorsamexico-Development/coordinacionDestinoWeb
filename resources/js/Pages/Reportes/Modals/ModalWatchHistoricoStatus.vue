<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },

  });
  
  const close = () => {    
     emit('close');
  };
    
</script>
<template>
   <DialogModal :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between">
            <h1>Historial</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
          <div class="grid grid-cols-2">
            <div class="flex">

            </div>
            <div>
                hola
            </div>
          </div>
       </template>
   </DialogModal>
</template>
    