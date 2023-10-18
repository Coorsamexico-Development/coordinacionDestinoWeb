<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, watch } from "vue";
import { router } from '@inertiajs/vue3'
//Importaciones
import ScrollableStatus from './Partials/ScrollableStatus.vue'

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


</script>
<template>
   <AppLayout title="Dashboard">
   
       <div class="grid grid-cols-4 gap-4 ">
           <div class="w-full col-start-4 px-2 py-4">
              <TextInput v-model="buscador" class="w-full px-2 py-1 bg-transparent" placeholder="Buscar" />
           </div>
       </div>
       <div class="grid grid-cols-3 gap-4 px-8 py-2 w-full">
          <div v-for="statu_padre in status_padre" :key="statu_padre.id">
             <ScrollableStatus :statu="statu_padre" :contadores="contadores" />
             <div class="px-4 py-4 rounded-lg snap-2" style="overflow-y: scroll;">
                <div class="bg-white rounded-xl drop-shadow-lg my-4 p-4" v-for="ubicacion in ubicaciones" :key="ubicacion.id">
                   {{ ubicacion.nombre_ubicacion }} 
                </div>   
             </div>
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
