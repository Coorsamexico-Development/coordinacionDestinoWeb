<script setup>
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import TextInput from '@/Components/TextInput.vue';
 import axios from 'axios';

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
     <DialogModal  :show="show" @close="close()">
        <template #title>
         <div class="flex flex-row justify-between">
            <h1>OCS</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content>
         
       </template>
     </DialogModal>
</template>