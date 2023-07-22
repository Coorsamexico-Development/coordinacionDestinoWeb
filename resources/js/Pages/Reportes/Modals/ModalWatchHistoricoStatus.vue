<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import axios from 'axios';
 import Campo from '../Partials/Campo.vue';

  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      infoModal:Object,
      status:Object
  });

  const camposValores = ref([]);
  const tamañoModal = ref('md')
  
  const close = () => { 
     emit('close');
     camposValores.value = [];
     tamañoModal.value = 'md';
  };

  const consultarHistoria = async (historiaIndividual) => 
  {
   try 
   {
    await axios.get('/checkValores', {params:
   {
      status_id: historiaIndividual.status_id,
      confirmacion_dt_id: historiaIndividual.confirmacion_dt_id
   }}).then(response => 
      {
         //console.log(response);
         tamañoModal.value = '2xl'
         camposValores.value= response.data;
      }).catch(err => 
      {
        console.log(err)
      });
   }
  catch(err)
  {
    console.log(err)
  }
  }
    
</script>
<template>
   <DialogModal :maxWidth="tamañoModal" :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between">
            <h1>Historial</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
          <div class="grid w-full grid-cols-2 gap-4">
             <div>
               <div class="flex flex-row gap-4" v-for="histori in infoModal" :key="histori.id">
                  <h1 class="text-l">{{ histori.status }}</h1>
                  <ButtonWatch class="w-8 h-6" :color="histori.color" @click="consultarHistoria(histori)" />
                  <div>
                    <span>Ultima actualizacion</span>
                    <br />
                    {{ histori.updated_at.substring(0,10) +' '+histori.updated_at.substring(11,19) }}
                  </div>
               </div>
             </div>
             <div style="overflow-y: scroll;">
                <div v-if="camposValores !== 0">
                   <div v-for="campoValor in camposValores" :key="campoValor.id">
                     <Campo :campoValor="campoValor" />
                   </div>
                </div>
             </div>
          </div>
       </template>
   </DialogModal>
</template>
    