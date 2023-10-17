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
  const ocs = ref([]);

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
              console.log(historiaIndividual.status_id)
              console.log(response);
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
    if(newStatus.status_id == 10 || newStatus.status_id == 11)
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
    console.log(pdf.value.pdf.substring(70));
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
   const activeClass = ref('timeline');
   const errorClas = ref('timeline2');
</script>
<template>
   <DialogModal :maxWidth="tamañoModal" :altura="'88%'"  :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between" style="font-family: 'Montserrat';">
            <h1>Historial</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
         <div class="grid w-full grid-cols-2" style="font-family: 'Montserrat';">
           <div class="">
             <h1 class="text-center">Histórico de status</h1>
             <!--TimeLine-->
             <div v-for="historia in infoModal" :key="historia.id">
               <div  :class="[historia.status.substring(0,8) !== 'Liberada'  ?  activeClass :  errorClas ]" class="border-b-2"  >
                  <div  id="timeline-item" >
                     <div id="timeline-icon"   :style="{backgroundColor:historia.color}" >
                     </div> 
                     <div id="timeline-content">
                       <div class="flex flex-row justify-between">
                          <div>
                             <h2 class="text-md" :style="{color:historia.color}">{{ historia.status }} </h2>
                             <div class="flex flex-row items-center">
                                <img class="w-3 h-3 mr-2" src="../../../../assets/img/reloj-de-pared.png" />
                                <h1 class="text-[#9B9B9B] text-xs">{{historia.created_at.substring(8,10) +'/'+historia.created_at.substring(5,7)+'  '+ ' ' +historia.created_at.substring(11,16) }}</h1>
                              </div>
                          </div>
                           <div class="flex items-center justify-center">
                             <ButtonWatch class="w-8 h-6" :color="'#44BFFC'" @click="consultarHistoria(historia)" />
                          </div>
                       </div>
                     </div>
                  </div>
               </div>
             </div>
               <!--TimeLine-->
           </div>
           <div>
             <h1 class="" style="text-align: center;">Información</h1>
             <div class="overflow-y-auto h-96 ">   
               <div class="snap-center" v-if="statusActual !== null">
                  <div v-if="statusActual.status_id !== 10 || statusActual.status_id !== 11 ">
                    <div v-if="camposValores.length !== 0">
                       <div class="">
                        <Campo :camposValores="camposValores" />
                       </div>
                       <div class="border-t-2 mt-2 ml-16 " v-if="statusActual.status_id == 9">
                           <h1 class="text-lg mt-2">OC's</h1>
                           
                       </div>
                     </div>
                 </div>
               </div>
               <div class="mt-8 snap-center" v-if="statusActual.status_id">
                    <div v-if="pdf !== null">
                       <div class="flex flex-row justify-center">
                           <h1 class="mx-2">Ver documento final</h1>
                           <a :href="pdf.pdf" data-fancybox   data-type="pdf">
                              <ButtonWatch :color="'#1D96F1'" />
                           </a>
                       </div>
                       <div class="grid grid-rows-3 mx-2">
                        <div class="w-full row-start-1 px-2">
                           <InputLabel>
                              Para:
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
                              Asunto:
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
<style>
  .timeline{
   width: 90%;
   margin: 30px auto;
   line-height: 1.5em;
   position: relative;
   transition: all 0.3s;
   
  }

  .timeline,
  .timeline *,
  .timeline *::before
  .timeline *::after{
   box-sizing: border-box;
  }

  .timeline::before{
   content: "";
   width: 7px;
   height: 190%;
   left: 0%;
   top:0;
   position: absolute;
   background-color: #9B9B9B ;
   opacity: 0.61;
  }

  .timeline #timeline-item{
   position: relative;
  }

  .timeline #timeline-item #timeline-icon
  {
   width: 30px;
   height: 30px;
   position: absolute;
   top:0;
   left:-3.5%;
   right: 0%;
   border-radius: 50%;
   justify-content: center;
   border-color: white;
   border-width: 2px;
  }

  .timeline #timeline-item #timeline-content
  {
    width: 100%;
    padding-left:10%;
    position: relative;
    border-radius: 5px;
  }


  /**/
  .timeline2{
   width: 90%;
   margin: 30px auto;
   line-height: 1.5em;
   position: relative;
   transition: all 0.3s;
   
  }

  .timeline2,
  .timeline2 *,
  .timeline2 *::before
  .timeline2 *::after{
   box-sizing: border-box;
  }

  .timeline2::before{
   content: "";
   width: 7px;
   height: 60%;
   left: 0%;
   top:0;
   position: absolute;
   background-color: #9B9B9B ;
  }

  .timeline2 #timeline-item{
   position: relative;
  }

  .timeline2 #timeline-item #timeline-icon
  {
   width: 30px;
   height: 30px;
   position: absolute;
   top:0;
   left:-3.5%;
   right: 0%;
   border-radius: 50%;
   justify-content: center;
   border-color: white;
   border-width: 2px;
  }

  .timeline2 #timeline-item #timeline-content
  {
    width: 100%;
    padding-left:10%;
    position: relative;
    border-radius: 5px;
  }
</style>
    