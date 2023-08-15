<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ScrollableStatus from './Partials/ScrollableStatus.vue'
import Scrollable from './Partials/ScrollableWithOutChilds.vue'
import TextInput from '@/Components/TextInput.vue';

var props = defineProps({
    status_padre:Object,
    ubicaciones:Object,
    plataformas:Object,
    contadores:Object
});
</script>
<template>
   <AppLayout title="Dashboard">
       <div class="grid grid-cols-4 gap-4 ">
           <div class="w-full col-start-4 px-2 py-4">
              <TextInput class="w-full px-2 py-1 bg-transparent" placeholder="Buscar" />
           </div>
       </div>
       <div class="grid grid-cols-4 gap-4 m-6">
         <div v-for="statu in status_padre" :key="statu.id">
            <div v-if="statu.id !==12">
                <ScrollableStatus  :statu="statu" :ubicaciones="ubicaciones" :plataformas="plataformas" :contadores = 'contadores' />
            </div>
            <div v-else>
                <div v-for="statu_hijo in statu.status_hijos" :key="statu_hijo.id">
                    <Scrollable :statu="statu_hijo" :ubicaciones="ubicaciones" :plataformas="plataformas"  />
                </div>
            </div>
        </div>
       </div>
    </AppLayout>
</template>
