<script setup>
  import {ref, watch, computed, reactive } from "vue";
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import TextInput from '@/Components/TextInput.vue';
  import ListInput from '@/Components/ListInput.vue';
  import SelectComponent from '@/Components/SelectComponent.vue'
  import axios from "axios";

  const props = defineProps({
       show: {
           type: Boolean,
           default: false,
       },
       incidencias:Object,
       productos:Object,
       tipos_incidencias:Object,
       oc: Object
   });

   const emit = defineEmits(["close",'reconsultar'])

   let newIncidencias = ref([]);
   const close = () => 
   { 
      emit('close');
      newIncidencias.value = [];
   };

  
   const addNewIncidencia = () => 
   {
       newIncidencias.value.push({
         sku: '',
         producto:'',
         tipo_incidencia_id:-1,
         reportePOD:0
       });
   }

   const saveNewIncidencias = () => 
   {
     //console.log(newIncidencias)
     try 
     {
       axios.post(route('saveNewIncidencias',{
        oc_id: props.oc.id,
        incidencias: newIncidencias.value
       })).
       then(response =>
       {
        //console.log(response.data)
        console.log(props.oc)
        newIncidencias.value = [];
        emit('reconsultar')
        //close();
       }).catch(
        err => {
          console.log(err)
        }
       );
     } 
     catch (error) 
     {
      
     }
   }

   const borrarIncidencia = (id) => 
   {
    //console.log(props.incidencias.length)
    axios.post(route('borrarIncidencia',{
      incidencia_id: id,

    })).then(response => {
      //console.log(response.data)
      //console.log(props.incidencias.length)
      emit('reconsultar')
    })
    .catch(err => 
    {
      console.log(err)
    })
   }

   const inputChange = (e, incidencia_id) => 
   {
    // console.log(e);
    // console.log(incidencia_id)
    axios.post(route('reportePOD',{
      incidencia_id: incidencia_id,
      valor: e
    })).then(response => {
      console.log(response.data)
      emit('reconsultar')
    })
    .catch(err => 
    {
      console.log(err)
    })
   }

</script>
<template>
  <DialogModal :maxWidth="'5xl'"  :show="show" @close="close()">
     <template #title>
       <div class="flex flex-row justify-between">
          <h1>Incidencias</h1>
          <a :href="route('downloadIncidenciasReport',{oc:oc.id})">
             <button  class="bg-[#44BFFC] px-8 py-2 rounded-2xl ">
               <img class="w-3" src="../../../../assets/img/down_arrow.png" />
                   <!--
                    <a :href="route('donwloadExportExample')" class="text-white">
                       Descargar ejemplo
                   </a>
                   -->
             </button>
          </a>
          <span @click="close()">
             Cerrar
          </span>
       </div>
     </template>
     <template #content>
        <table class="w-full" style="font-family: 'Montserrat';">
            <thead class="border-b-2">
                <tr >
                    <td class="text-center font-semibold pb-2"></td>
                    <td class="text-center font-semibold pb-2">SKU</td>
                    <td class="text-center font-semibold pb-2">Producto</td>
                    <td class="text-center font-semibold pb-2">Tipo de incidencia</td>
                    <td class="text-center font-semibold pb-2">Cantidad</td>
                    <td class="text-center font-semibold pb-2">Evidencias</td>
                    <td class="text-center font-semibold pb-2">Reporte POD</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="incidencia in incidencias" :key="incidencia.id">
                    <td class="text-center text-sm py-2 px-4">
                      <button class="bg-[#E86881] px-2 py-1 rounded-full" @click="borrarIncidencia(incidencia.id)" v-if="incidencia.cantidad == 0 || incidencia.evidencias.length ==0 ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                      </button>
                    </td>
                    <td class="text-center text-sm py-2 px-4">
                      {{ incidencia.sku }}
                    </td>
                    <td class="text-center text-sm py-2 px-4">
                      {{ incidencia.producto }}
                    </td>
                    <td class="text-center py-2 px-4">
                        {{ incidencia.tipo_incidencia }}
                    </td>
                    <td class="text-center py-2 px-4">
                        {{ incidencia.cantidad }}
                    </td>
                    <td class="flex items-center justify-center py-2 px-4">
                      <div v-if="incidencia.evidencias.length > 0">
                        <div v-for=" (evidencia, key) in incidencia.evidencias" :key="evidencia.id">
                          <div >
                               <a v-if="key == 0 "  :href="evidencia.evidencia"  :data-fancybox="'gallery'">
                                <ButtonWatch class="w-8 h-6" :color="'#44BFFC'"  />
                               </a>
                               <a v-else class="hidden" :href="evidencia.evidencia"  :data-fancybox="'gallery'">
                                 <ButtonWatch class="w-8 h-6" :color="'#44BFFC'"  />
                               </a>
                           </div>
                         </div>
                      </div>
                    </td>
                    <td class="text-center">
                       <div v-if="incidencia.cantidadPOD">
                          <TextInput  :value="incidencia.cantidadPOD" @input="inputChange($event.target.value, incidencia.id)"  />
                       </div>
                       <div v-else>
                         <TextInput @input="inputChange($event.target.value, incidencia.id)"  />
                       </div>
                    </td>
                </tr>
                <tr class="border-t-2" v-for="(newIncidencia, key) in newIncidencias" :key="key" >
                  <td class="w-2 text-center py-2" colspan="3">
                     <ListInput :options="productos" v-model="newIncidencia.sku"   list="listaProductos"/>
                  </td>
                  <td class="text-center py-2">
                     <SelectComponent v-model="newIncidencia.tipo_incidencia_id">
                      <option v-for="tipo_incidencia in tipos_incidencias" :value="tipo_incidencia.id" :key="tipo_incidencia.id" >
                         {{ tipo_incidencia.nombre }}
                      </option>
                    </SelectComponent>
                  </td>
                  <td class="text-center py-2">
                    - 
                  </td>
                  <td class="text-center py-2">
                     -
                  </td>
                  <td class="text-center py-2">
                    <TextInput v-model="newIncidencia.reportePOD" />
                  </td>
               </tr>
            </tbody>
          </table>
          <div class="flex justify-center mt-4" v-if="newIncidencias.length !== 0">
              <button @click="saveNewIncidencias()" class="text-white bg-[#44BFFC] px-10 py-4 rounded-full">
                 Guardar
              </button>
          </div>
          <div class="flex justify-center mt-4">
             <button @click="addNewIncidencia()" class="p-2 px-4 border-2 rounded-full">
               <p class="text-xl font-bold text-red-400">+</p>
            </button>
          </div>
     </template>
   </DialogModal>
</template>