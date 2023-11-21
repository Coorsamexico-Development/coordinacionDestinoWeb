<script setup>
  import { ref, watch } from 'vue';
  import DialogModal from '@/Components/DialogModal.vue';
  import ButtonWatch from '@/Components/ButtonWatch.vue';
  import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
  import '@fancyapps/ui/dist/fancybox/fancybox.css';
  import ListInput from '@/Components/ListInput.vue';
  import SelectComponent from '@/Components/SelectComponent.vue';
  import TextInput from '@/Components/TextInput.vue';
  import axios from 'axios';
  
  const emit = defineEmits(["close", "reconsultarOcsIncidencias"])
  const props = defineProps({
      show: {
          type: Boolean,
          default: false,

      },
      incidencias:Object,
      oc:Object,
      dt:Number
  });

  
  let productos = ref([]);
  let tipo_incidencias = ref([]);
  axios.get('/getTiposIncidenciaYProductos').then(response => 
    {
       //console.log(response.data)
       productos.value = response.data.productos;
       tipo_incidencias.value = response.data.tipos_incidencia;
    }).catch(err => 
    {

    })

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
   });

  const tamañoModal = ref('2xl')
  const close = () => 
  { 
     emit('close');
  };

  const eliminarIncidencia = (incidencia) => //funcion para eliminar las evidencias de la bd
  {
    //console.log(incidencia)
    axios.post(route('borrarIncidencia',{
      incidencia_id: incidencia,

    })).then(response => {
      //console.log(response.data)
      //console.log(props.incidencias.length)
        emit("reconsultarOcsIncidencias", props.dt, props.oc.id)
    })
    .catch(err => 
    {
      console.log(err)
    })
  }

  let newInidencias = ref([]);
  const addNewIncidencia = () => 
  {
    newInidencias.value.push({
         sku: '',
         tipo_incidencia_id:-1,
         cantidad: '0'
       });
  }

  const saveNewIncidencias = () => 
  {
    //console.log(newInidencias)
    //console.log(props.oc.id)
    //console.log(props.dt)
    axios.get(route('saveIncidenciasByOc',{oc:props.oc.id, incidencias:newInidencias.value, confirmacion:props.dt})).then(response => 
    {
       //console.log(response.data)
       newInidencias.value = [];
       emit("reconsultarOcsIncidencias", props.dt, props.oc.id)
    }).then(err => {

    })
    
    
  }

  const eliminarNuevaIncidencia = (key) => 
  {
    //console.log(key)
    let incidenciasNuevas = newInidencias.value.filter((newIncidencia, index) => 
    {
      index !== key
    });

    newInidencias.value = incidenciasNuevas;
    //console.log(incidenciasNuevas)
  }

</script>
<template>
   <DialogModal :maxWidth="tamañoModal"   :show="show" @close="close()">
    <template #title>
         <div class="flex flex-row justify-between" style="font-family: 'Montserrat';">
            <h1>Incidencias</h1>
            <span @click="close()">
               Cerrar
            </span>
         </div>
       </template>
       <template #content  >
          <table class="w-full" style="font-family: 'Montserrat';">
            <thead>
                <tr class="border-b ">
                    <td class="px-6"></td>
                    <td class="font-semibold text-center">Producto</td>
                    <td class="font-semibold text-center">Tipo de incidencia</td>
                    <td class="font-semibold text-center">Cantidad</td>
                    <td class="font-semibold text-center">Evidencias</td>
                </tr>
            </thead>
            <tbody>
                <tr v-for="incidencia in incidencias" :key="incidencia.id">
                    <td class="py-2">
                      <button  v-if="$page.props.auth.user.cans['delete-incidencia-customer']" @click="eliminarIncidencia(incidencia.id)" class="px-1 py-1 bg-red-500 rounded-lg">
                         <img class="w-3 h-4" src="../../../../assets/img/eliminar.png" />
                      </button>
                    </td>
                    <td class="py-2 text-sm text-center">
                      {{ incidencia.producto }}
                    </td>
                    <td class="py-2 text-center">
                        {{ incidencia.tipo_incidencia }}
                    </td>
                    <td class="py-2 text-center">
                        {{ incidencia.cantidad }}
                    </td>
                    <td class="flex items-center justify-center py-2">
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
                </tr>
                <tr v-for="(newIncidencia, key) in newInidencias" :key="key">
                   <td class="py-2 text-center">
                    <button v-if="$page.props.auth.user.cans['delete-incidencia-customer']" @click="eliminarNuevaIncidencia(key)" class="px-1 py-1 bg-red-500 rounded-lg">
                         <img class="w-3 h-4" src="../../../../assets/img/eliminar.png" />
                      </button>
                   </td>
                   <td class="py-2 text-center">
                     <ListInput class="w-56" :options="productos" v-model="newIncidencia.sku"   list="listaProductos"/>
                   </td>
                   <td class="py-2 text-center">
                    <SelectComponent class="w-30" v-model="newIncidencia.tipo_incidencia_id">
                      <option v-for="tipo_incidencia in tipo_incidencias" :value="tipo_incidencia.id" :key="tipo_incidencia.id" >
                         {{ tipo_incidencia.nombre }}
                      </option>
                    </SelectComponent>
                   </td>
                   <td class="py-2 text-center">
                     <TextInput class="w-14" v-model="newIncidencia.cantidad" />
                   </td>
                   <td class="py-2 text-center">
                      -
                   </td>
                </tr>
            </tbody>
          </table>
          <div class="flex justify-center mt-4" v-if="newInidencias.length !== 0">
              <button @click="saveNewIncidencias()" class="text-white bg-[#44BFFC] px-10 py-4 rounded-full">
                 Guardar
              </button>
          </div>
          <div class="flex justify-center mt-2">
            <button v-if="$page.props.auth.user.cans['create-new-incidencia-customer']" @click="addNewIncidencia()" class="p-1 px-3 border-2 rounded-full">
               <p class="text-lg font-bold text-red-400">+</p>
            </button>
          </div>
       </template>
   </DialogModal>
</template>