<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {ref, watch, computed, reactive } from "vue";
import { router } from '@inertiajs/vue3'
import PaginationAxios from '@/Components/PaginationAxios.vue';

var props = defineProps({
    productos:Object,
});



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
                <p class="text-white">
                    Descargar ejemplo
                </p>
            </button>
          </div>
          <div class="mx-4">
             <button class="flex flex-row bg-[#1D96F1] px-8 py-2 rounded-2xl">
                <img class="w-4 mr-2" src="../../../assets/img/DOCS.png" >
                <p class="text-white">
                    Subir nuevos productos 
                </p>
             </button>
          </div>
       </div>
    </template>
     <div class="py-4 m-8 bg-white rounded-2xl" style="font-family: 'Montserrat';">
        <table class="w-full">
            <thead>
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
                <tr v-for="producto in productos.data" :key="producto.producto_id">
                  <td class="text-center">
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
                     <button class="bg-[#697FEA] px-4 py-1 rounded-2xl mt-2">
                        <img class="w-8" src="../../../assets/img/eye.png" />
                     </button>
                  </td>
                </tr>
            </tbody>
        </table>
     </div>
  </AppLayout>
</template>