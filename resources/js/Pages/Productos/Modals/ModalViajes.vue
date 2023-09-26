<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

 const emit = defineEmits(["close"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      producto:Object
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
         
       </template>
     </DialogModal>
</template>