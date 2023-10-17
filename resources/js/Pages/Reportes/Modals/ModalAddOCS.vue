<script setup> 
 import {ref, watch, computed, reactive } from "vue";
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
    <DialogModal :maxWidth="'2xl'" :show="show" @close="close()">
        <template #title>
          <div class="flex flex-row justify-between">
             <h1>OCS</h1>
             <span @click="close()">
                Cerrar
             </span>
          </div>
        </template>
        <template #content>
            hola
        </template>
    </DialogModal>
</template>