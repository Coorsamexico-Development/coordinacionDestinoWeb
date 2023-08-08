<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBoxContador from './Partials/StatusBoxContador.vue';
import PlataformaBoxContador from './Partials/PlataformaBoxContador.vue';
var props = defineProps({
   status:Object,
   plataformas:Object
});
</script>
<template>
   <AppLayout title="Reportes">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Reportes
            </h2>
        </template>
       <div class="grid grid-cols-3 gap-4">
          <div class="p-4">
              <div class="w-80"> <!--Totales-->
                 <h1 class="mb-8 text-xl font-bold">Totales</h1>
                 <div class="px-4" style="overflow-y: scroll; overflow-x: hidden; height: 20rem;">
                    <div v-for="statu in status" :key="statu.id">
                       <div v-for="statu_hijo in statu.status_hijos" :key="statu_hijo.id">
                          <StatusBoxContador :statu="statu_hijo" />
                       </div>
                    </div>
                 </div>
              </div>
              <div>
                  
              </div>
          </div>
          <div>
             <div class="p-4">
                <div class="flex flex-row justify-between">
                    <h1 class="mb-8 text-xl font-bold">
                        Embarques
                    </h1>
                    <div>
                        Switch
                    </div>
                </div>
                <div class="flex flex-row justify-between">
                    <div  v-for="plataforma in plataformas" :key="plataforma.id">
                        <PlataformaBoxContador :plataforma="plataforma" />
                    </div>
                </div>
             </div>
          </div>
          <div>

          </div>
       </div>
    </AppLayout>
</template>