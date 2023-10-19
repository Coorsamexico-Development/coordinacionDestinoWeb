<script setup>
  import { ref, watch } from 'vue';
  import DialogModal from '@/Components/DialogModal.vue';
  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      }
    ,
    incidencias:Object
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
          {{ incidencias }}
          
       </template>
   </DialogModal>
</template>