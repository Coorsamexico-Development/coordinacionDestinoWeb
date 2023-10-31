<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, watch, onMounted } from "vue";
import { router } from '@inertiajs/vue3'
//Importaciones
import ScrollableStatus from './Partials/ScrollableStatus.vue'
import Pusher from 'pusher-js' 
import Echo from 'laravel-echo';

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

let pusher = new Pusher('ec1646c4d112ae02864d', { 
  cluster: 'us2', 
  activityTimeout:123,
  pongTimeout:456
 });

const reconect = () => 
{
  setTimeout(function() 
     {
       console.log(pusher.connection.state);
       if(pusher.connection.state == 'disconnected')
       {
         pusher.subscribe('confirmacion')
         connect();
       }
       else
       {
        console.log('its ok')
       }
     },30000)
}

const connect = () => 
{
    pusher.subscribe('confirmacion')
    pusher.bind('notification', data => 
     {
        console.log(data)
        router.visit(route('reportes.index'), 
        {
          preserveScroll:true,
          preserveState:true,
          replace:true,
          only:['contadores','ubicaciones']
        })
     }); 


     setTimeout(function() 
     {
       reconect();
     },30000)
}




onMounted(() => 
{
  connect();

})
/*
 window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ec1646c4d112ae02864d',
    encrypted: true,
    cluster:'us2',
    activityTimeout:50000
});
window.Echo.channel('confirmacion')
    .listen('notification', (e) => {
        console.log(e);
    });
*/
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
