<script setup> 
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
 import '@fancyapps/ui/dist/fancybox/fancybox.css';

  const emit = defineEmits(["close"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      viajes:Object
  });

  const close = () => { 
     emit('close');
  };

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
 });

 let infoModal = ref(null);
 let status = ref([]);
 //Funcion modales
 let modalWatch = ref(false);
 const modalWatchOpen = (id) => 
{
  modalWatch.value=true;
  axios.get(route('showHistorico'), {params:{
   id:id
  }}).then(response =>
  {
    console.log(response);
    infoModal.value = response.data.historico;
    status.value = response.data.status;
  }).catch(err => 
  {
   console.log(err)
  })
}
const modalWatchClose = () => 
{
  modalWatch.value=false;
}

</script>
<template>
       <DialogModal maxWidth="4xl"  :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between">
            <h1>Viajes</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >

          <table class="w-full text-center">
            <thead class="">
               <tr class="border-b border-[#44BFFC]">
                  <th>
                     <h1 class="">Confirmación</h1>
                  </th>
                  <th>
                     DT
                  </th>
                  <th>
                     Cita
                  </th>
                  <th>
                     Número de cajas
                  </th>
                  <th>
                     Linea de transporte
                  </th>
                  <th>
                     Plataforma
                  </th>
                  <th>
                     Documento final
                  </th>
                  <th>
                     Historico
                  </th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="viaje in viajes" :key="viaje.id">
                  <td>
                     {{ viaje.confirmacion }}
                  </td>
                  <td>
                     {{ viaje.dt }}
                  </td>
                  <td>
                     {{ viaje.cita }}
                  </td>
                  <td>
                     {{ viaje.numero_cajas }}
                  </td>
                  <td>
                     {{ viaje.linea_transporte }}
                  </td>
                  <td>
                     {{ viaje.plataforma }}
                  </td>
                  <td class="flex justify-center">
                     <a :href="viaje.pdf" data-fancybox   data-type="pdf">
                        <ButtonWatch :color="'#1D96F1'" />
                     </a>
                  </td>
                  <td >
                     <ButtonWatch @click="modalWatchOpen(viaje.dt_id)" :color="'#1D96F1'" />
                  </td>
               </tr>
            </tbody>
          </table>
       </template>
   </DialogModal>
   
</template>