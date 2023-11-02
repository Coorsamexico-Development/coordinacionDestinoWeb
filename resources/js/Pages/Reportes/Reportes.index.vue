
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, watch, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
//Importaciones
import ScrollableStatus from './Partials/ScrollableStatus.vue'
import 'izitoast/dist/css/iziToast.min.css';
import iziToast from 'izitoast';

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
    only:['contadores','ubicaciones']
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
      /*
      if(data.confirmacionDt)
      {
        iziToast.show({ 
        position:'topRight',
        title: 'Confirmacion: '+ data.confirmacionDt.confirmacion +'</br>'+'Referencia: '+data.confirmacionDt.dt,
        backgroundColor: '#56D0C1',
        theme: 'light',
        iconUrl:'https://www.freeiconspng.com/thumbs/alert-icon/alert-icon-png-rss-short-for-real-pictures-22.png',
        message: 'Cambio al status ' + data.confirmacionDt.status})
         
        router.visit(route('reportes.index'), 
          {
            preserveScroll:true,
            preserveState:true,
            replace:true,
            only:['contadores','ubicaciones']
          })
      }
      */
      //app.messages.push(JSON.stringify(data));
    });
  });




</script>

<template>
   <AppLayout title="Dashboard">
       <div class="grid grid-cols-4 gap-4 ">
           <div class="w-full col-start-4 px-2 py-4">
              <TextInput v-model="buscador" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar" />
           </div>
       </div>
       <div class="grid w-full grid-cols-3 gap-4 px-8 py-2">
          <div v-for="statu_padre in status_padre" :key="statu_padre.id">
             <ScrollableStatus :statu="statu_padre" :contadores="contadores" :ubicaciones="ubicaciones" :plataformas="plataformas" :buscador="buscador" />
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
