<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import axios from 'axios';
 import Campo from '../Partials/Campo.vue';
 import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
  import '@fancyapps/ui/dist/fancybox/fancybox.css';
 import DropFile from '@/Components/DropFile.vue';
 import { useForm } from '@inertiajs/vue3'
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
     pdf.value = null;
  };

  const statusActual = ref(null);

  const consultarHistoria = async (historiaIndividual) => 
  {
   statusActual.value = historiaIndividual;
   try 
   {
    await axios.get('/checkValores', {params:
   {
      status_id: historiaIndividual.status_id,
      confirmacion_dt_id: historiaIndividual.confirmacion_dt_id
   }}).then(response => 
      {
         //console.log(response);
         tamañoModal.value = '4xl'
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
  
 Fancybox.bind("[data-fancybox]", {
    // Your custom options
 });

 const pdf = ref(null);
 watch(statusActual, (newStatus) => 
 {
   console.log(newStatus)
    if(newStatus.status_id == 5 || newStatus.status_id == 4)
    {
       axios.get('/getPDF', {params:
       {
          id:newStatus.confirmacion_dt_id
       }}).then(response => 
          {
             console.log(response);
             pdf.value = response.data;
          }).catch(err => 
          {
            console.log(err)
          });
       }
    
 });


 const enviarCorreo = () => 
 {
    axios.get('/sentMail', {params:
       {
       }}).then(response => 
       {
          console.log(response.data);
       }).catch(err => 
       {
         console.log(err)
       });
 }
  /*
  Prueba para subida de archivos 
  let file = ref(null)
  let formDoc = useForm({
   'file': null
  })

  watch(file, (documentoCargado) => 
 {
     formDoc.file = documentoCargado;
     formDoc.post(route('valoresEnrrampe'));
 });
    */
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
               <table>
                  <tr v-for="histori in infoModal" :key="histori.id">
                     <td class="px-2">
                        <h1 class="text-l">{{ histori.status }}</h1>
                     </td>
                     <td class="px-2">
                        <ButtonWatch class="w-8 h-6" :color="histori.color" @click="consultarHistoria(histori)" />
                     </td>
                     <td class="px-2">
                        <span>Ultima actualizacion</span>
                        <br />
                        {{ histori.updated_at.substring(0,10) +' '+histori.updated_at.substring(11,19) }}
                     </td>
                  </tr>
               </table>
             </div>
             <div style="overflow-y: scroll; height: 20rem;">
               <div v-if="statusActual !== null">
                  <div v-if="statusActual.status_id !== 5 || statusActual.status_id !== 4 ">
                    <div v-if="camposValores !== 0">
                       <div>
                        <Campo :camposValores="camposValores" />
                       </div>
                     </div>
                     <div v-else>
                       <h3>No hay información</h3>
                     </div>
                 </div>
                 <div v-if="statusActual.status_id">
                    <div v-if="pdf !== null">
                       <div class="flex flex-row">
                           <h1 class="mx-2">Ver documento</h1>
                           <a :href="pdf.pdf" data-fancybox   data-type="pdf">
                              <ButtonWatch :color="'#1D96F1'" />
                           </a>
                       </div>
                       <div class="">
                          <button @click ="enviarCorreo">
                             Enviar correo
                          </button>
                       </div>
                    </div>
                 </div>  
               </div>          
             </div>
          </div>
       </template>
   </DialogModal>
</template>
    