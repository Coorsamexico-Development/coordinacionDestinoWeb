<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusBoxContador from './Partials/StatusBoxContador.vue';
import PlataformaBoxContador from './Partials/PlataformaBoxContador.vue';
import { onMounted, reactive, ref, watch, computed } from "vue";
import GraficaTotales from './Partials/GraficaTotales.vue'
var props = defineProps({
   status:Object,
   plataformas:Object
});

const contadorGlobal = computed(() =>
{
    let totales = [];
    for (let index = 0; index < props.plataformas.length; index++) 
    {
      const plataforma = props.plataformas[index];
      for (let index2 = 0; index2 < plataforma.confirmaciones_dts.length; index2++) 
      {
         const confirmaciones = plataforma.confirmaciones_dts[index2];
         totales.push(confirmaciones);
      }
    } 

    return {
      tipo:'DTS',
      cantidad:totales.length
     }
});

</script>
<template>
   <AppLayout title="Reportes">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Reportes
            </h2>
        </template>
       <div class="grid grid-cols-6 gap-4">
          <div class="p-4">
              <div class="w-full"> <!--Totales-->
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
          <div class="w-full col-start-2 col-end-5">
             <div class="p-4">
                <div class="flex flex-row justify-between">
                    <h1 class="mb-8 text-xl font-bold">
                        Embarques
                    </h1>
                    <div>
                        Switch
                    </div>
                </div>
                <div class="flex flex-row justify-between bg-white rounded-lg">
                    <div class="px-2 py-2"  v-for="plataforma in plataformas" :key="plataforma.id">
                        <PlataformaBoxContador :plataforma="plataforma" />
                    </div>
                    <div class="flex flex-row justify-between px-8 py-2 rounded-lg" style="background: transparent linear-gradient(180deg, #5DC9FF 0%, #25ADF0 100%) 0% 0% no-repeat padding-box;">
                       <div class="flex flex-col items-center">
                           <h1 class="text-4xl font-semibold text-white" style="font-family: 'Montserrat';">
                              {{contadorGlobal.cantidad}}
                           </h1>
                          <h1 class="text-sm text-white uppercase" style="font-family: 'Montserrat';">
                             {{ contadorGlobal.tipo }}
                          </h1>
                       </div>
                    </div>
                </div>
             </div>
             <div>
               <div class="p-4 bg-white rounded-lg">
                  <h1>Totales</h1>
                  <GraficaTotales />
               </div>
             </div>
          </div>
          <div>

          </div>
       </div>
    </AppLayout>
</template>