<script setup>
 import { ref, watch } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';

 const emit = defineEmits(["close"])
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
</script>
<template>
  <DialogModal :maxWidth="'sm'"  :show="show" @close="close()">
       <template #content>
          <div class="flex justify-center bg-[#F2F2F2] rounded-full p-20">
            <svg width="160px" height="160px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
               <path d="M15 18L17 20L21 16M11 19H6.2C5.0799 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V12M20.6067 8.26229L15.5499 11.6335C14.2669 12.4888 13.6254 12.9165 12.932 13.0827C12.3192 13.2295 11.6804 13.2295 11.0677 13.0827C10.3743 12.9165 9.73279 12.4888 8.44975 11.6335L3.14746 8.09863" stroke="#56D0C1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="w-full mt-4">
               <div>
                 <h1 class="my-2 text-lg text-center">
                    Correo enviado correctamente
                </h1>
              </div>
              <button @click="close()" class="bg-[#1D96F1] text-white px-4 w-full rounded-full text-xl">
                  Aceptar
              </button>
          </div>
       </template>
  </DialogModal>
</template>