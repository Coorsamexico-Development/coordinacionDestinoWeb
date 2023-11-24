
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, watch, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
//Importaciones
import ScrollableStatus from './Partials/ScrollableStatus.vue'
//import 'izitoast/dist/css/iziToast.min.css';
//import iziToast from 'izitoast';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import Element from './Partials/Elements.vue'
//Driver js
import { driver } from "driver.js";
import "driver.js/dist/driver.css";


var props = defineProps({
    status_padre:Object,
    ubicaciones:Object,
    plataformas:Object,
    contadores:Object
});

const buscador = ref('');

watch(buscador, (newBusqueda) => 
{
  router.visit(route('reportes.index'), {
    preserveScroll:true,
    preserveState:true,
    replace:true,
    data:{busqueda:newBusqueda},
    only:['contadores','ubicaciones', 'plataformas']
  })

});


onMounted(() => 
{
  // connect();
    // Echo.channel(`confirmacion`)
    // .listen('notification', (e) => {
    //     console.log(e);
    // });

    /*
    Echo.channel(`orders.1`)
    .listen('OrderShipmentStatusUpdated', (data) => 
    {
        console.log(data);
    });
*/
    var pusher = new Pusher('3fdfa63c24d55954bcee', 
    {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('confirmacion');
    channel.bind('notification', function(data) 
    {
      console.log(data)
      if(data.confirmacionDt)
      {
        /*
        iziToast.show({ 
        position:'topRight',
        title: 'Confirmacion: '+ data.confirmacionDt.confirmacion +'</br>'+'Referencia: '+data.confirmacionDt.dt,
        backgroundColor: '#56D0C1',
        theme: 'light',
        iconUrl:'https://www.freeiconspng.com/thumbs/alert-icon/alert-icon-png-rss-short-for-real-pictures-22.png',
        message: 'Cambio al status ' + data.confirmacionDt.status})
         */
         toast.info(
           Element,
         {
            autoClose: false,
            dangerouslyHTMLString: true,
            data:data.confirmacionDt
         }); // ToastOptions
        router.visit(route('reportes.index'), 
          {
            preserveScroll:true,
            preserveState:true,
            replace:true,
            only:['contadores','ubicaciones']
          })
      }
      
      //app.messages.push(JSON.stringify(data));
    });
  });


  const iniciarRecorrido  = async () => 
  {
   //Funciones de driverjs
   const driverObj = driver({
    showProgress:true,
    allowClose:true,
    nextBtnText: 'Siguiente',
    prevBtnText:'Anterior',
    doneBtnText: 'Finalizar',
    steps: [
    { element: '#buscadorViajes', popover: { title: 'Buscador de viajes', description: 'Este es un buscador de los viajes cargados, se pueden buscar tanto por confirmacion como su dt del viaje.', side: "left", align: 'start', onNextClick: () => {
          driverObj.moveNext();
        } }},
    { element: '#container-status', popover: { title: 'Dashboard principal de viajes', description: 'Este es todo el contenedor de los viajes, los cuales se seccionan por diferentes estatus que a su vez estos tienen los estatus con los cuales se identifican los viajes. Todos estos cambios van en conjunto con la aplicación movil para el registro de información y el avance del viaje.', side: "top", align: 'start', onNextClick: () => {
      driverObj.moveNext();
    } }},
    { element: '#status-name-1', popover: { title: 'Estatus general', description: 'Este es un ejemplo de un estatus general de los que se muestran dentro de la página.', side: "top", align: 'start', onNextClick: () => {
      driverObj.moveNext();
    } }},
    { element: '#status-1', popover: { title: 'Status iniciales', description: 'Esta sección contiene los viajes con status iniciales, por default se deben carga los viajes en el status "A tiempo" si llega a haber un riesgo que se marque desde la aplicación movil se mostrara en tiempo real el cambio.', side: "bottom", align: 'start', onNextClick: () => {
          driverObj.moveNext();
        } }},
     { element: '#downloadReport', popover: { title: 'Descarga de ejemplo de viajes', description: 'Este botón te brindara un excel de ejemplo para el llenado de informacion para la carga de información de nuevos viajes.', side: "bottom", align: 'start', onNextClick: () => {
       driverObj.moveNext();
     } }},
    { element: '#dropzone', popover: { title: 'Carga de nuevos viajes', description: 'Botón de importación para la carga de nuevos viajes.', side: "bottom", align: 'start', onNextClick: () => {
          driverObj.moveNext();
        } }},
    { element: '#status-2', popover: { title: 'Status de movimiento', description: 'Estos son estatus de movilidad del viaje, que de igual manera se registran los cambios e información mediante la aplicación.', side: "bottom", align: 'start',  onNextClick: () => {
          driverObj.moveNext();
        } }},
   ],
   });
   
    driverObj.drive();
  }


</script>

<template> 
   <AppLayout title="Dashboard">
       <div class="grid grid-cols-4 gap-4 ">
           <div class="px-2 py-4">
              <button @click="iniciarRecorrido()" class="bg-[#697FEA] px-2 rounded-full">
                <p style="font-family: 'Montserrat';" class="text-white">Manual de usuario</p>        
              </button>
           </div>
           <div class="w-full col-start-4 px-2 py-4">
              <TextInput id="buscadorViajes" v-model="buscador" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar" />
           </div>
       </div>
       <div class="grid w-full grid-cols-3 gap-4 px-8 py-2" id="container-status">
          <div v-for="(statu_padre) in status_padre" :key="statu_padre.id">
             <ScrollableStatus  :statu="statu_padre" :contadores="contadores" :ubicaciones="ubicaciones" :plataformas="plataformas" :buscador="buscador" />
          </div>
       </div>
    </AppLayout>
</template>
<style>
  ::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 10px #c6c6c6; 
  border-radius: 1px;
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #C5C5C5; 
  border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #C5C5C5; 
}


</style>
