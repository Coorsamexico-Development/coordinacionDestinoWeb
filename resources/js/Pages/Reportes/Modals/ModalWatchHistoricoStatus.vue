<script setup>
 import { ref, watch, reactive } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import TextInput from '@/Components/TextInput.vue';
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
  const tamañoModal = ref('2xl')
  
  const close = () => { 
     emit('close');
     camposValores.value = [];
     tamañoModal.value = '2xl';
     pdf.value = null;
  };

  const statusActual = ref(null);

  const consultarHistoria = async (historiaIndividual) => 
  {
   //console.log(historiaIndividual)
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
         let camposWithValors = [];
         if(response.data.valors.length > 0)
         {
            for (let index = 0; index < response.data.campos.length; index++) 
            {
               const campo = response.data.campos[index];
               let campoWithValors = {
                  campo_id: campo.campo_id,
                  campo: campo.campo,
                  tipo_campo: campo.tipo_campo,
                  valores: []
               }
               for (let index2 = 0; index2 < response.data.valors.length; index2++) 
               {
                  const valor = response.data.valors[index2];
                  if(valor.campo_id == campo.campo_id)
                  {
                     campoWithValors.valores.push(valor);
                  }
               }
               camposWithValors.push(campoWithValors)
            }
         }
         console.log(camposWithValors)
          camposValores.value = camposWithValors;
         //camposValores.value= response.data;
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
    if(newStatus.status_id == 5 || newStatus.status_id == 4)
    {
       axios.get('/getPDF', {params:
       {
          id:newStatus.confirmacion_dt_id
       }}).then(response => 
          {
             //console.log(response);
             pdf.value = response.data;
          }).catch(err => 
          {
            console.log(err)
          });
       }
       else
       {
         pdf.value = null;
       }
    
 });

 const email = ref('');
 const emails = ref([]);
 const asunto = ref('');
 const agregarCorreo = () =>
 {
    if(email.value !== "")
    {
      //console.log(email.value)
      emails.value.push(email.value);
      email.value = '';
    }
    else
    {

    }
 }
 const eliminarEmail = (emailSelect) => 
 {
    emails.value.filter(email => email !== emailSelect );
 }

 const enviarCorreo = () => 
 {
    //console.log(pdf.value.pdf.substring(70));
    axios.get('/sentMail', {params:
       {
         emails:emails.value,
         asunto:asunto.value,
         pdf:pdf.value.pdf.substring(70)
       }}).then(response => 
       {
          //console.log(response.data);
          email.value = '';
          emails.value = [];
          asunto.value = '';
          alert(response.data);          
       }).catch(err => 
       {
         console.log(err)
         alert(err);  
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
               <div class="flex flex-row justify-between border-[#9B9B9B] border-b-2 pb-2" v-for="histori in infoModal" :key="histori.id">
                 <div class="flex flex-row">
                    <div class="mx-2 mt-2">
                       <span :style="{backgroundColor:histori.color}" class="block w-4 h-4 mx-2 rounded-full"></span>
                    </div>
                    <div class="flex flex-col ">
                       <h1 class="text-lg" :style="{color:histori.color}">{{ histori.status }}</h1>
                      <div class="flex flex-row items-center">
                        <img class="w-4 h-4 mr-2" src="../../../../assets/img/reloj-de-pared.png" />
                        <h1 class="text-[#9B9B9B] text-sm">{{ histori.created_at.substring(0,10) +' '+histori.created_at.substring(11,19) }}</h1>
                      </div>
                   </div>
                 </div>
                 <div class="flex items-center justify-center">
                    <ButtonWatch class="w-8 h-6" :color="'#44BFFC'" @click="consultarHistoria(histori)" />
                 </div>
             </div>
            </div>
             <div style="overflow-y: scroll; overflow-x: hidden; height: 20rem;">
               <div v-if="statusActual !== null">
                  <div v-if="statusActual.status_id !== 10 || statusActual.status_id !== 11 ">
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
                           <h1 class="mx-2">Ver documento final</h1>
                           <a :href="pdf.pdf" data-fancybox   data-type="pdf">
                              <ButtonWatch :color="'#1D96F1'" />
                           </a>
                       </div>
                       <div class="grid grid-rows-3 mx-2">
                        <div class="w-full row-start-1 px-2">
                           <InputLabel>
                              Para
                           </InputLabel>
                            <TextInput v-model="email" class="w-full h-8">
                            </TextInput>
                            <button class="bg-[#44BFFC] text-white px-2 py-1 rounded-lg m-2" @click="agregarCorreo()" >
                                Agregar correo
                            </button>
                           <div class="grid grid-cols-2">
                              <div class="flex flex-row bg-blue-200 rounded-2xl" v-for="email in emails" :key="email.id">
                                 <button @click="eliminarEmail(email)" class="px-1 text-red-500">
                                    x
                                 </button>
                                  {{ email }}
                              </div>
                           </div>
                        </div>
                        <div class="row-start-2 mt-4">
                           <InputLabel>
                              Asunto
                           </InputLabel>
                           <textarea v-model="asunto" class="w-full"></textarea>
                        </div>
                        <div class="row-start-3">
                           <button class="bg-[#4f595e] text-white px-2 py-1 rounded-lg"  @click ="enviarCorreo">
                             Enviar correo
                           </button>
                        </div>
                       </div>
                    </div>
                 </div>  
               </div>          
             </div>
          </div>
       </template>
   </DialogModal>
</template>
    