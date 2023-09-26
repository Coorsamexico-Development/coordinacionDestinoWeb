<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref, watch, computed, reactive } from "vue";
import { router, Link, useForm  } from '@inertiajs/vue3'
import PaginationAxios from '@/Components/PaginationAxios.vue';
import ButtonUploadProd from './Partials/ButtonUploadProd.vue'
import ModalViajes from './Modals/ModalViajes.vue'
import axios from 'axios';

var props = defineProps({
    productos:Object,
});

const productosData = ref(props.productos.data);
const productosChange = ref(props.productos);
//Form
const formNewProductos = useForm({
  document: null,
})
const document = ref(null) 

const loadPage = (page) => 
{
    axios.get(page)
    .then(response => 
    {
       productosData.value = response.data.data; //seteamos la info
       productosChange.value = response.data;
    })
    .catch(e => {
        // Podemos mostrar los errores en la consola
        console.log(e);
    })
}

//Watcher para la carga del reporte
watch(document, (documentoCargado) => 
{
    formNewProductos.document = documentoCargado
   try 
   {
      if(formNewProductos.document !== null)
      {
         console.log(formNewProductos)
         formNewProductos.post(route('productos.store'),
         {
            onSuccess: () => {
               formNewProductos.reset();
               document.value = null;
            },
            onError:(err) => 
            {
              console.log(err);
              formNewProductos.reset();
              document.value = null;
            }
         }
         );
      }
   } 
   catch (error) 
   {
     console.log(error)  
   }
});

const showModalViaje = ref(false);
const productoActual = ref({});
let viajes = ref([]);
const opennModalViaje = (prod) => 
{
    productoActual.value = prod;
    showModalViaje.value = true;
    //una vez con el producto consultamos
    axios.get(route('viajesByProducto'),{
        params:
        {
            producto:productoActual.value
        }
    }).then(response =>
    {
      console.log(response);
      viajes.value = response.data;
    }).catch(err => {
        console.log(err);
    })
}
const closeModalViaje = () => 
{
    showModalViaje.value = false;
    productoActual.value = {};
}
</script>
<template>
  <AppLayout title="Productos">
    <template #header>
       <div class="flex flex-row mt-2 align-middle" style="font-family: 'Montserrat';">
          <h2 class="mr-4 text-xl font-semibold leading-tight text-gray-800" style="font-family: 'Montserrat';">
              Productos
          </h2>
          <div class="mr-2">
            <button class="bg-[#44BFFC] px-8 py-2 rounded-2xl flex flex-row">
                <img class="w-4 mr-2" src="../../../assets/img/down_arrow.png" />
                <a :href="route('donwloadExportExample')" class="text-white">
                    Descargar ejemplo
                </a>
            </button>
          </div>
          <div class="mx-4">
             <ButtonUploadProd v-model="document" />
          </div>
       </div>
    </template>
     <div class="py-4 m-8 bg-white rounded-2xl" style="font-family: 'Montserrat';">
        <table class="w-full">
            <thead class="border-b-2">
                <tr>
                    <th class="font-semibold">SKU</th>
                    <th class="font-semibold">Descripción</th>
                    <th class="font-semibold">DUN 14</th>
                    <th class="font-semibold">EAN</th>
                    <th class="font-semibold">Activo</th>
                    <th class="font-semibold">Creación</th>
                    <th class="font-semibold">Viajes</th>
                </tr>
            </thead>
            <tbody>
                <tr class="" v-for="producto in productosData" :key="producto.producto_id">
                  <td class="flex flex-row justify-center text-center align-middle">
                    <button class="bg-[#F25B77] mr-2 px-2 py-1 rounded-full">
                        <img class="w-4" src="../../../assets/img/eliminar.png" />
                    </button>
                     {{ producto.producto_SKU }}
                  </td>
                  <td class="text-center">
                     {{ producto.producto_descripcion }}
                  </td>
                  <td class="text-center">
                     {{ producto.producto_dun14 }}
                  </td>
                  <td class="text-center">
                    {{ producto.producto_ean }}
                  </td>
                  <td class="text-center">
                      <div v-if="producto.producto_activo">
                        <svg class="w-6 h-6 m-auto text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      </div>
                      <div v-else>
                        <svg class="w-6 h-6 m-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                      </div>
                  </td>
                  <td class="text-center">
                    {{ producto.producto_creacion }}
                  </td>
                  <td class="text-center">
                     <button @click="opennModalViaje(producto)" class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                        <img class="w-8" src="../../../assets/img/eye.png" />
                     </button>
                  </td>
                </tr>
            </tbody>
        </table>
        <PaginationAxios :pagination="productosChange" @loadPage="loadPage($event)" />
     </div>
     <ModalViajes :show="showModalViaje" :producto="productoActual" @close="closeModalViaje()" :viajes="viajes" />
  </AppLayout>
</template>