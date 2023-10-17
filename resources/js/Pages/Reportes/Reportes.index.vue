<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ScrollableStatus from './Partials/ScrollableStatus.vue'
import Scrollable from './Partials/ScrollableWithOutChilds.vue'
import TextInput from '@/Components/TextInput.vue';
import {ref, watch, computed, reactive } from "vue";
import { router } from '@inertiajs/vue3'

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
       <div class="grid grid-cols-3 gap-4 px-4 py-2">
        
         <div v-for="statu in status_padre" :key="statu.id">
            <div v-if="statu.id !==3 || statu.id !== 3">
                <ScrollableStatus :buscador="buscador" :statu="statu" :ubicaciones="ubicaciones" :plataformas="plataformas" :contadores = 'contadores' />
            </div>
             <!--
            <div v-else>
                <div v-for="statu_hijo in statu.status_hijos" :key="statu_hijo.id">
                    <Scrollable :buscador="buscador" :statu="statu_hijo" :ubicaciones="ubicaciones" :plataformas="plataformas"  />
                </div>
            </div>
             -->
          </div>
       
       </div>

    </AppLayout>
</template>
