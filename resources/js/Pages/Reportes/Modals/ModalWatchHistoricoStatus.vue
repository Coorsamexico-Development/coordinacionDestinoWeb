<script setup>
 import { ref, watch } from 'vue';
 import DialogModal from '@/Components/DialogModal.vue';
 import ButtonWatch from '@/Components/ButtonWatch.vue';
 import InputLabel from '@/Components/InputLabel.vue';
 import TextInput from '@/Components/TextInput.vue';
 import axios from 'axios';
 import Campo from '../Partials/Campo.vue';
 import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
 import '@fancyapps/ui/dist/fancybox/fancybox.css';
 import ModalIncidencias from '../Modals/ModalIncidencias.vue';
 import VueDatePicker from '@vuepic/vue-datepicker';
 import '@vuepic/vue-datepicker/dist/main.css';
 import SpinProgress from "@/Components/SpinProgress.vue";
 import ModalOk from './ModalOk.vue';
 import ModalErr from './ModalErr.vue';

  const emit = defineEmits(["close", "reVisit"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,
      },
      infoModal:Object,
      status:Object,
      viaje:Number,
      dt:Object
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
              //console.log(historiaIndividual.status_id)
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
             // console.log(camposWithValors)
               camposValores.value = camposWithValors;
              //camposValores.value= response.data;
      }).catch(err => 
      {
        console.log(err)
      });

      //Consulta para las OCS
      axios.get('/ocsByViaje',{
         params:{
            confirmacion_dt_id: historiaIndividual.confirmacion_dt_id
         }
      }).then(response => {
         //console.log(response)
         ocs.value = response.data;
      }).catch(err => {
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
    //console.log(emailSelect)
    //console.log(emails.value)
    let newEmails = emails.value.filter(email => email !== emailSelect );

    emails.value = newEmails;
 }

 let spinnerSend = ref(false);
 const enviarCorreo = () => 
 {
    spinnerSend.value = true;
    //console.log(pdf.value.pdf.substring(70));
    let newPdf = null;
    //console.log(pdf.value.pdf)
    if(pdf.value.pdf !== null)
    {
      newPdf = pdf.value.pdf.substring(70)
    }
    else
    {
      newPdf = ''
    }
    axios.get('/sentMail', {params:
       {
         emails:emails.value,
         asunto:asunto.value,
         pdf:newPdf,
         viaje:props.viaje
       }}).then(response => 
       {
          //console.log(response.data);
          email.value = '';
          emails.value = [];
          asunto.value = '';
          spinnerSend.value = false
          openModalOk()
          //alert(response.data);          
       }).catch(err => 
       {
         console.log(err)
         console.log(err)
         openModalErr()
         spinnerSend.value = false
         //alert(err);  
       });
 }

 const modalIncidencias = ref(false);
 const incidencias = ref(null);
 const openModalIncidencias = (oc) => 
 {
   modalIncidencias.value = true;
   incidencias.value = oc.incidencias
 }

 const closeModalIncidencias = () =>
 {
   modalIncidencias.value = false;
 }

 let showThings = ref(false)
 let ocActual = ref(-1);
 const mostrar = (oc) => 
 {
   ocActual.value = oc
   showThings.value = !showThings.value
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
   const cambio = (id) => 
   {
      axios.get(route('changeToRiesgo'),{params:{id:id}}).then(response=>{
        console.log(response.data)
       }).catch(err => 
        {
          console.log(err.response.data)
        });
   }

   const activeClass = ref('timeline');
   const errorClas = ref('timeline2');

   const cita = ref(props.dt.cita);
   let spinnerCita = ref(false)
   watch(cita, (newCita) => 
   {
      let year = newCita.getFullYear();
      let month = newCita.getMonth()+1;
      let day = newCita.getDate();
      let hours = newCita.getHours();
      let minutes = newCita.getMinutes();
      spinnerCita.value = true;
      if(day < 10)
      {
        day = '0'+day;
      }

      if(month < 10)
      {
         month = '0'+month;
      }

      if(hours < 10)
      {
         hours = '0'+hours;
      }

      if(minutes < 10)
      {
         minutes = '0'+minutes;
      }

      //console.log(year+'-'+month+'-'+day+' '+hours+':'+minutes )
      let newFecha = year+'-'+month+'-'+day+' '+hours+':'+minutes ;
      //console.log(newFecha)
      //console.log()
      try 
      {
         axios.get(route('changeCita',{fecha:newFecha, viaje:props.dt.id})).then(response => 
         {
           //console.log(response.data)
           spinnerCita.value = false;
           emit('reVisit',props.dt.id);
         })
         .catch(err => 
         {

         })
      } 
      catch (error) {
         
      }
   });

   const consultarOcsIncidencias = (item, oc) => 
   {
      //console.log(props.dt.id)
      //Consulta para las OCS
       axios.get('/ocsByViaje',{
        params:{
           confirmacion_dt_id: item
        }
      }).then(response => {
         //console.log(response)
         ocs.value = response.data;
         let ocIn = ocs.value.filter(ocL => ocL.id == oc);
         //console.log(ocIn);
         //console.log(ocIn[0].incidencias)
         incidencias.value = ocIn[0].incidencias;
      }).catch(err => {
         console.log(err)
      });
      
      //console.log(props.viaje)
      emit('reVisit',item);
   }

   const ok = ref(false); 
   const err = ref(false)
   const  openModalOk = () => 
   {
      ok.value = true;
   }

   const closeModalOk = () => 
   {
      ok.value = false
   }

   const  openModalErr = () => 
   {
      err.value = true;
   }

   const closeModalErr = () => 
   {
      err.value = false
   }

</script>
<template>
   <DialogModal :maxWidth="tamañoModal" :altura="'88%'"  :show="show" @close="close()">
       <template #title>
         <div class="flex flex-row justify-between" style="font-family: 'Montserrat';">
            <h1>Historial</h1>
            <span id="cerrar-modal-historico" @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
         <div class="my-3" id="cambio-de-cita">
            <h1 class="mx-2 font-semibold" style="font-size: 1rem ;">Cita:</h1>
            <div class="flex flex-row items-center"> 
               <VueDatePicker v-model="cita" class="mx-2"  vertical placeholder="Cita" :teleport="true" />
               <SpinProgress v-if="spinnerCita"  :inprogress="true" />
            </div>
         </div>
         <div class="grid w-full grid-cols-2" style="font-family: 'Montserrat';">
           <div class="my-4" id="historico-status">
             <h1 class="text-xl font-semibold text-center">Histórico de status</h1>
             <!--TimeLine-->
             <div  v-for="(historia, key) in infoModal" :key="historia.id">
               <!--
               <button @click="cambio(historia.confirmacion_dt_id)">
                  Cambio
               </button>0
               -->
               <div  :class="[key+1 !== infoModal.length  ?  activeClass :  errorClas ]"  >
                  <div  id="timeline-item" >
                     <div id="timeline-icon"  class="flex items-center"  :style="{backgroundColor:historia.color}" >
                        <img class="w-8" :src="historia.icon" v-if="historia.status == 'Enrampado'" />
                        <img class="w-4" :src="historia.icon" v-else-if="historia.status == 'Documentado'" />
                        <img class="w-5" :src="historia.icon" v-else />
                     </div> 
                     <div id="timeline-content">
                       <div class="flex flex-row justify-between">
                          <div>
                             <h2 class="text-lg" :style="{color:historia.color}">{{ historia.status }} </h2>
                             <div class="flex flex-row items-center">
                                <img class="w-4 h-4 mr-2" src="../../../../assets/img/reloj-de-pared.png" />
                                <h1  class="text-[#9B9B9B] text-base">{{historia.created_at.substring(8,10) +'/'+historia.created_at.substring(5,7)+'  '+ ' ' +historia.created_at.substring(11,16) }}</h1>
                              </div>
                          </div>
                           <div class="flex items-center justify-center">
                             <ButtonWatch class="w-8" :color="'#44BFFC'" @click="consultarHistoria(historia)" />
                          </div>
                       </div>
                     </div>
                     <div class="absolute bg-[#9B9B9B] w-11/12 opacity-25 ml-9 mt-2" style="height: 2px;">
                     </div>
                  </div>
               </div>
             </div>
               <!--TimeLine-->
           </div>
           <div id="valores-by-status" class="my-4">
             <h1 class="text-xl font-semibold text-center" style="text-align: center;">Información</h1>
             <div class="overflow-y-auto" style="height:30rem;" v-if="statusActual !== null">   
               <div class="snap-center" >
                  <div v-if="statusActual.status_id !== 10 || statusActual.status_id !== 11 ">
                    <div v-if="camposValores.length !== 0">
                       <div class="">
                        <Campo :camposValores="camposValores" :status="statusActual" />
                       </div>
                       <div class="mt-2 border-t-2 " v-if="statusActual.status_id == 9">
                           <div class="flex flex-row mt-2 justify-evenly">
                              <h1 class="mt-2 text-lg">Oc's</h1>
                              <a :href="route('downloadIncidenciasReport', {viaje:viaje})">
                                 <button  class="bg-[#44BFFC] px-8 py-2 rounded-2xl ">
                                   <img class="w-3" src="../../../../assets/img/down_arrow.png" />
                                 </button>
                              </a>
                           </div>
                           <div  class="p-4 mx-2 my-4 bg-white rounded-lg drop-shadow-lg" v-for="oc in ocs" :key="oc.id">
                              <div @click="mostrar(oc)" class="flex justify-between py-1">
                                 <h1 class="text-lg font-semibold">{{ oc.referencia }}</h1>
                                 <div>
                                    <svg  v-if="showThings" class="mx-2" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                                      <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
                                    </svg>    
                                    <svg  v-if="!showThings" class="mx-2 rotate-180" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                                      <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
                                    </svg>
                                 </div>
                              </div>
                              <div >
                                 <Transition name="slide-fade" class="mt-2 border-t-2">
                                  <div v-if="showThings && (oc.id == ocActual.id)" >
                                     <table class="w-full mt-2">
                                        <thead>
                                           <tr>
                                              <td class="text-center">Facturado</td>
                                              <td class="text-center">En POD</td>
                                              <td class="text-center">Incidencias</td>
                                           </tr>
                                        </thead>
                                        <tbody>
                                           <tr>
                                              <td class="text-center">{{oc.facturado}}</td>
                                              <td class="text-center">{{oc.enPOD}}</td>
                                              <td class="flex justify-center">
                                                 <ButtonWatch @click="openModalIncidencias(oc)" class="w-8 h-6" :color="'#44BFFC'" />
                                              </td>
                                           </tr> 
                                        </tbody>
                                     </table>
                                  </div>
                                </Transition>
                              </div>
                           </div>
                       </div>
                     </div>
                 </div>
               </div>
               <div v-if="camposValores.length == 0 && statusActual.status_id !== 11 && statusActual.status_id !== 10"> 
                  <h1 class="mt-4 text-lg font-semibold text-center">No hay información en este paso</h1>
               </div>
               <div class="mt-8 snap-center" v-if="statusActual.status_id">
                    <div v-if="pdf !== null">
                       <div class="mx-4 my-6">
                           <h1 class="mb-2 text-lg font-semibold" style="font-family: 'Montserrat';">Ver documento final</h1>
                           <a :href="pdf.pdf" data-fancybox   data-type="pdf">
                              <ButtonWatch :color="'#1D96F1'" />
                           </a>
                       </div>
                       <div class="grid grid-rows-3 mx-2 my-6">
                        <div class="w-full row-start-1 px-2">
                           <h1 class="text-lg font-semibold" style="font-family: 'Montserrat';">Envio de correo</h1>
                           <div class="flex flex-row items-center justify-center justify-items-center">
                              <div>
                                 <TextInput v-model="email" class="w-full h-8" placeholder="Para: ejemplo@.com" type="email" required>
                                 </TextInput>
                              </div>
                              <button class="bg-[#44BFFC] text-white px-2 py-2 rounded-lg m-2 flex flex-row justify-between" @click="agregarCorreo()" >
                                <svg class="mx-1" width="20px" height="20px"  viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path fill="" d="M16 17H21M18.5 14.5V19.5M12 19H6.2C5.0799 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V11M20.6067 8.26229L15.5499 11.6335C14.2669 12.4888 13.6254 12.9165 12.932 13.0827C12.3192 13.2295 11.6804 13.2295 11.0677 13.0827C10.3743 12.9165 9.73279 12.4888 8.44975 11.6335L3.14746 8.09863" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="" style="font-size: 13px;">
                                  Agregar correo
                                </p>  
                              </button>
                           </div>
                           <div class="grid grid-cols-2 mt-2">
                              <div class="flex flex-row py-1 mx-1 my-1 bg-blue-200 rounded-2xl" v-for="email in emails" :key="email.id">
                                 <button @click="eliminarEmail(email)" class="px-1 mx-1 bg-red-500 rounded-full">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                      <path d="M17 16L22 21M22 16L17 21M13 19H6.2C5.0799 19 4.51984 19 4.09202 18.782C3.71569 18.5903 3.40973 18.2843 3.21799 17.908C3 17.4802 3 16.9201 3 15.8V8.2C3 7.0799 3 6.51984 3.21799 6.09202C3.40973 5.71569 3.71569 5.40973 4.09202 5.21799C4.51984 5 5.0799 5 6.2 5H17.8C18.9201 5 19.4802 5 19.908 5.21799C20.2843 5.40973 20.5903 5.71569 20.782 6.09202C21 6.51984 21 7.0799 21 8.2V12M20.6067 8.26229L15.5499 11.6335C14.2669 12.4888 13.6254 12.9165 12.932 13.0827C12.3192 13.2295 11.6804 13.2295 11.0677 13.0827C10.3743 12.9165 9.73279 12.4888 8.44975 11.6335L3.14746 8.09863" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                 </button>
                                  {{ email }}
                              </div>
                           </div>
                        </div>
                        <div class="row-start-2 mt-4">
                           <InputLabel class="my-2 font-semibold">
                              Asunto:
                           </InputLabel>
                           <textarea v-model="asunto" class="w-full"></textarea>
                        </div>
                        <div class="row-start-3 my-2" v-if="emails.length > 0">
                           <button class="bg-[#4f595e] text-white px-2 py-2 rounded-lg flex flex-row items-center"  @click ="enviarCorreo">
                              <svg class="mx-2" width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                               <path d="M19 21V15M19 15L17 17M19 15L21 17M21 11V8.2C21 7.0799 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.0799 3 8.2V15.8C3 16.9201 3 17.4802 3.21799 17.908C3.40973 18.2843 3.71569 18.5903 4.09202 18.782C4.51984 19 5.0799 19 6.2 19H13M20.6067 8.26229L15.5499 11.6335C14.2669 12.4888 13.6254 12.9165 12.932 13.0827C12.3192 13.2295 11.6804 13.2295 11.0677 13.0827C10.3743 12.9165 9.73279 12.4888 8.44975 11.6335L3.14746 8.09863" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                               Enviar correo
                             <SpinProgress v-if="spinnerSend" :inprogress="true" class="ml-2" :fill="'white'" />
                           </button>
                        </div>
                       </div>
                    </div>
                 </div>  
             </div>
           </div>
         </div>
         <ModalIncidencias :incidencias="incidencias"  
         :show="modalIncidencias" 
         :oc="ocActual" 
         :dt="dt.id" 
         @close="closeModalIncidencias()" 
         @reconsultarOcsIncidencias="consultarOcsIncidencias" />

         <ModalOk :show="ok" @close="closeModalOk" />
         <ModalErr :show="err" @close="closeModalErr" />
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
   opacity: 0.50;
  }

  .timeline #timeline-item{
   position: relative;
  }

  .timeline #timeline-item #timeline-icon
  {
   width: 38px;
   height: 38px;
   position: absolute;
   top:0;
   left:-4.5%;
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
   opacity: 0.50;
  }

  .timeline2 #timeline-item{
   position: relative;
  }

  .timeline2 #timeline-item #timeline-icon
  {
   width: 38px;
   height: 38px;
   position: absolute;
   top:0;
   left:-4.5%;
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

  /*Transicion*/ 
  .slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translatey(-20px);
  opacity: 0;
}

</style>
    