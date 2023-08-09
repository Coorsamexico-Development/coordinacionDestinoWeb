<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3'
import StatusBoxContador from './Partials/StatusBoxContador.vue';
import PlataformaBoxContador from './Partials/PlataformaBoxContador.vue';
import Switch from './Partials/Switch.vue'
import { onMounted, reactive, ref, watch, computed } from "vue";
import GraficaTotales from './Partials/GraficaTotales.vue'
import ButtonCalendar from '@/Components/ButtonCalendar.vue';
import { pickBy, throttle } from "lodash";

var props = defineProps({
   status:Object,
   plataformas:Object,
   ubicaciones:Object,
   status_graph:Object
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
      tipo:'Confirmaciones',
      cantidad:totales.length
     }
});

const dataGrafica = computed(() =>
{
   let finalData = [];
   for (let index = 0; index < props.ubicaciones.length; index++) 
   {
      const ubicacion = props.ubicaciones[index];
      let newObject = 
       {
          id: ubicacion.id,
          ubicacion:ubicacion.nombre_ubicacion,
       };  

       //Todas las ubicaciones tendran los mismos status pero hay que setear el conteo por ubicacion
      // console.log(ubicacion.id)
       //Recorremos los status y los steamos por ubicacion
       for (let index2 = 0; index2 < props.status_graph.length; index2++) 
       {
         const status = props.status_graph[index2];   
         let contador = [];   
        // newObject[status.nombre] = 0;
         for (let index3 = 0; index3 < status.confirmaciones_dts.length; index3++) 
         {
            const confirmacion = status.confirmaciones_dts[index3];
            //console.log(confirmacion.ubicacion_id)
            if(ubicacion.id == confirmacion.ubicacion_id)
            {
              //console.log('son iguales')
              contador.push(confirmacion);
            }
         }

       
        newObject[status.nombre] = contador.length;
       }     
      finalData.push(newObject);
   }

   return finalData;
});

//Fechas
let date = ref({
    month: new Date().getMonth(),
    year: new Date().getFullYear(),
});

//Filtros
const params = reactive({
    tipo: null,
    fecha:null,
});

const changeDate = (newDate) => {
    date.value = newDate;
    let fecha = null;
    //console.log(newDate.month)
    switch (newDate.month) 
    {
      case 0: //Enero
            fecha = newDate.year + '-' + "01";
            params.fecha = fecha;
         break;
      case 1: //Febrero
            fecha = newDate.year + '-' + "02";
            params.fecha = fecha;
         break;
      case 2: //Marzo
            fecha = newDate.year + '-' + "03";
            params.fecha = fecha;
         break;
      case 3: //Abril
            fecha = newDate.year + '-' + "04";
            params.fecha = fecha;
         break;
      case 4: //Mayo
            fecha = newDate.year + '-' + "05";
            params.fecha = fecha;
         break;
      case 5: //Junio
         fecha = newDate.year + '-' + "06";
         params.fecha = fecha;
      break;
      case 6: //Julio
         fecha = newDate.year + '-' + "07";
         params.fecha = fecha;
      break;
      case 7: //Agosto
         fecha = newDate.year + '-' + "08";
         params.fecha = fecha;
      break;
      case 8: //Spetiembre
         fecha = newDate.year + '-' + "09";
         params.fecha = fecha;
      break;
      case 9: //Octubre
         fecha = newDate.year + '-' + "10";
         params.fecha = fecha;
      break;
      case 10: //Noviembre
         fecha = newDate.year + '-' + "11";
         params.fecha = fecha;
      break;
      case 11: //Diciembre
         fecha = newDate.year + '-' + "12";
         params.fecha = fecha;
      break;
    }
};

//watcher para filtros
watch(params, throttle(function () 
  {
    search();
 }), 100);

 const search = () => 
{
   const filters = pickBy(params);
   //console.log(filters);
   
   
   router.visit(route('reportes.graficos.index'),{
        data:filters,
        preserveScroll:true,
        preserveState:true,
        only:['status_graph','plataformas', 'status']
    }); 
   
   
}

</script>
<template>
   <AppLayout title="Reportes">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
                Reportes
            </h2>
        </template>
       <div class="grid grid-cols-6 gap-4">
          <div class="p-1">
              <div class="w-full"> <!--Totales-->
                 <h1 class="m-2 mb-8 text-xl font-bold">Totales</h1>
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
                    <ButtonCalendar :month="date.month"  :year="date.year"  @change-date="changeDate($event)" />
                    </div>
                    <div>
                        <Switch />
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
               <div class="w-full p-4 bg-white rounded-lg">
                  <h1 class="text-xl font-bold">Totales</h1>
                  <GraficaTotales :data="dataGrafica" :ubicaciones="ubicaciones" :status_graph="status_graph" />
               </div>
             </div>
          </div>
          <div>

          </div>
       </div>
    </AppLayout>
</template>