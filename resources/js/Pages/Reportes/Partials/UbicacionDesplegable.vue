<script setup>
import axios from "axios";
import {ref, watch, computed, reactive, onUpdated } from "vue";
import SwitchButton from './SwitchButton.vue';
import DtBlock from './DtBlock.vue';
import { pickBy } from 'lodash';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import { usePage, router } from '@inertiajs/vue3'
//Props
var props = defineProps({
    ubicacion:Object,
    plataformas:Object,
    status:Object,
    buscador:String,
    fecha:String
});

//Show para mostrar los hijos
let show = ref(false)
//Parametros de busqueda o filtros
const params = reactive({
   //siempre consultara la primer plataforma
    ubicacion_id: -1,
    plataforma_id:1,
    status_id:props.status.id,
    busqueda:'',
    fecha:''
});

onUpdated(() => 
{
  //console.log(props)
  params.busqueda = props.buscador;
  params.fecha = props.fecha
})

const showClients = (ubicacion_id) =>  //funcion para desplegar
{
    //console.log(ubicacion_id);
    params.ubicacion_id = ubicacion_id
    show.value = !show.value ;
}

//El id viene de la emicion de switchButtons
const setPlataforma = (id) => 
{
   params.plataforma_id = id;
}

//DTS
let dts = ref(null);
let nuevosParametros = ref({});
let dtsData = ref([]);
//Watcher para parametros
watch(params, (newParams) => 
{
  //console.log(newParams)
  if(newParams.ubicacion_id == undefined)
  {
    params.ubicacion_id = -1;
  }

  if(newParams.buscador !== '')
  {
    
  }

  if(newParams.buscador !== null)
  {
    
  }

 
  if(newParams.ubicacion_id !== -1)
  {
    axios.get(route('getConfirmacions',{
      ubicacion_id: newParams.ubicacion_id,
      plataforma_id: newParams.plataforma_id,
      status_id: newParams.status_id,
      busqueda: newParams.busqueda,
      fecha:newParams.fecha
    }))
      .then(response => {
          // Obtenemos los datos
          //console.log(response.data)
          nuevosParametros.value = {
            ubicacion_id: newParams.ubicacion_id,
            plataforma_id: newParams.plataforma_id,
            status_id: newParams.status_id,
            busqueda: newParams.busqueda,
            fecha: newParams.fecha
          }
          dts.value = response.data;
          //dtsData.value = response.data.data;
      })
      .catch(e => {
          // Capturamos los errores
      })
  }

});

//Reconsulta al paginado
const loadPage = async (page) =>
{
   console.log(page);
   console.log(params)
   //console.log(usePage().url)
   axios.get('/getConfirmaciones',
   {
     params:{
       page:page,
       ubicacion_id: params.ubicacion_id,
       plataforma_id: params.plataforma_id,
       status_id: params.status_id,
       busqueda: params.busqueda,
       fecha:params.fecha
     }
   }).then(resp => 
   {
     console.log(resp.data)
     dts.value = resp.data;
   });
   /*
  let newPage = 'https'+ page.substring(4);
  await axios.get(newPage,{
    params:{
      plataforma_id:nuevosParametros.value.plataforma_id,
      ubicacion_id:nuevosParametros.value.ubicacion_id,
      status_id:nuevosParametros.value.status_id,
      busqueda: nuevosParametros.value.busqueda,
    }
   })
    .then(response => {
       dts.value = response.data
       dtsData.value = response.data.data;
    })
    .catch(e => {
        // Podemos mostrar los errores en la consola
        console.log(e);
    })
    */
    
}


const valores = computed(() => 
{
  let contadorGeneral = []
  for (let index = 0; index < props.status.status_hijos.length; index++)
  {
    const statusHijo = props.status.status_hijos[index];
    let acumulo=[];
    for (let index2 = 0; index2 < props.ubicacion.confirmaciones_dts.length; index2++) 
    {
      const confirmacionDt = props.ubicacion.confirmaciones_dts[index2];
      if(statusHijo.id == confirmacionDt.status_id)
      {
        acumulo.push(confirmacionDt)

      }
    }
    contadorGeneral.push({status:statusHijo.id, statusName:statusHijo.nombre ,  total:acumulo.length})
  }

  return contadorGeneral
});

</script>
<template>
   <div class="bg-white rounded-xl drop-shadow-lg" :id="'ubicacion-'+ubicacion.id"> <!--main-->
     <div> <!--Header-->
        <div class="flex flex-row items-center justify-between p-4 mx-2 mt-4 bg-white rounded-lg">
          <h1 class="text-lg uppercase" style="font-family: 'Montserrat';">{{ ubicacion.nombre_ubicacion }}</h1>
          <div class="flex flex-row items-center">
            <div class="flex flex-row mr-2">    
              <div class="mx-4 text-3xl font-bold"  v-for="statuChild in status.status_hijos" :key="statuChild.id" :style="{color:statuChild.color}">
                <div v-for="(valor, key) in valores"  :key="key">
                   <div v-if="valor.status == statuChild.id">
                      <div class="flex flex-row" v-if="valor.status"> 
                        <p class="ml-0" :id="'ubicacion-contador'+key">
                          {{ valor.total }}
                        </p>
                        <span class="bg-[#9B9B9B] absolute h-7 mx-8 mt-1" style="width:2px" v-if="valor.status == 4">
                        </span>
                        <span class="bg-[#9B9B9B] absolute h-7 mx-8 mt-1" style="width:2px" v-else-if="valor.status == 6">
                        </span>
                        <span class="bg-[#9B9B9B] absolute h-7 mx-8 mt-1" style="width:2px" v-else-if="valor.status == 7">
                        </span>
                        <span class="bg-[#9B9B9B] absolute h-7 mx-8 mt-1" style="width:2px" v-else-if="valor.status == 8">
                        </span>
                        <span class="bg-[#9B9B9B] absolute h-7 mx-8 mt-1" style="width:2px" v-else-if="valor.status == 10">
                        </span>
                      </div>
                   </div>
                </div>
              </div>
            </div>
            <div id="boton-despliegue">
               <svg @click="showClients(ubicacion.id)" v-if="show" class="mx-2" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                 <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
               </svg>    
               <svg @click="showClients(ubicacion.id)" v-if="!show" class="mx-2 rotate-180" xmlns="http://www.w3.org/2000/svg" width="27.203" height="15.723" viewBox="0 0 27.203 15.723">
                 <path id="Trazado_4273" data-name="Trazado 4273" d="M0,0,11.48,11.48,22.96,0" transform="translate(25.081 13.602) rotate(180)" fill="none" stroke="#9b9b9b" stroke-linecap="round" stroke-width="3"/>
               </svg>
            </div>
          </div>
        </div>
     </div>
     <!--Contenido-->
     <Transition name="slide-fade">
        <div v-if="show" >
          <SwitchButton id="switch-plataformas" @setPlataforma="setPlataforma($event)" :plataformas="plataformas" :ubicacion="ubicacion" :status="status" />
          <div v-if="dts !== null">
             <!--SON CONFIRMACIONES las que se listan-->
             <div class="pb-1" :id="'dt-block'+key" v-for="(dt,key) in dts.data" :key="dt.id">
                <DtBlock :dt="dt"  />
             </div>
             <div class="py-2">
               <PaginationAxios @loadPage="loadPage($event)" :pagination="dts" />
             </div>             
          </div>
        </div>
     </Transition>
   </div>
</template>
<style>
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

/**/
.slide-fade2-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade2-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade2-enter-from,
.slide-fade2-leave-to {
  transform: translatex(20px);
  opacity: 0;
}
</style>