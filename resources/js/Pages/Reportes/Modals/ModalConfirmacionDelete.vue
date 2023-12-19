<script setup>
 import { ref, watch } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

 const emit = defineEmits(["close", "eliminaViaje"])
 const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      }
  });

  const close = () =>
  { 
     emit('close');
  };

  const confirmarEliminacion = () => 
  {
     emit('eliminaViaje')
  }
</script>
<template>
  <DialogModal :maxWidth="'lg'"  :show="show" @close="close()">
       <template #content>
          <h1 class="text-xl font-bold text-center">¿Desea eliminar este viaje con su información y registros?</h1>
          <div class="flex flex-row justify-around mt-8">
              <div class="">
                 <button @click="confirmarEliminacion()" class="px-4 py-2 text-white rounded-full bg-sky-500">
                    Aceptar
                 </button>
              </div>
              <div>
                 <button @click="close()" class="px-4 py-2 text-white bg-red-400 rounded-full">
                    Cancelar
                 </button>
              </div>
          </div> 
       </template>
  </DialogModal>
</template>