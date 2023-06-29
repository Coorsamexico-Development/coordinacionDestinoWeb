<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      infoModal:Object,
      status:Object
  });
  
  const close = () => {    
     emit('close');
  };
    
</script>
<template>
   <DialogModal :maxWidth="'md'" :show="show" @close="close()">
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
            <div v-for="histori in infoModal">
               {{ histori.status_id }}
            </div>
          </div>
       </template>
   </DialogModal>
</template>
    