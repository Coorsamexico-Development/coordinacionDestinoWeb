<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';


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
             <div>
               <div class="flex flex-row justify-between" v-for="histori in infoModal" :key="histori.id">
                 {{ histori.status }}
                <ButtonWatch :color="histori.color" />
               </div>
             </div>
             <div>

             </div>
          </div>
       </template>
   </DialogModal>
</template>
    