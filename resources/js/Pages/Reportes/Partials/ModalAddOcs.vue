<script setup>
  import DialogModal from '@/Components/DialogModal.vue';
  const emit = defineEmits(["close","reconsultar"])

  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       }
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
            hola
        </template>
     </DialogModal>
</template>