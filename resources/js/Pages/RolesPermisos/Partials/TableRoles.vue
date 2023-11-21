<script setup>
 import { ref, watch } from 'vue';
 
var props = defineProps({
    roles:Object
});

const emit = defineEmits(["selectRol"])

const filaActual = ref(null);
const selectFila = (fila) => 
{
  emit('selectRol', fila)
  filaActual.value = fila.id;
}


</script>
<template>
     <div class="w-full">
        <table class="w-full">
            <thead class="">
                <tr class="border-b-2 border-[#697FEA]">
                    <th class="font-semibold">Nombre</th>
                    <th class="font-semibold">Activo</th>
                </tr>
            </thead>
            <tbody class="">
                  <tr v-for="rol in roles" :key="rol.id" @click="selectFila(rol)" :class="{'bg-[#9B9B9B] text-white':(rol.id==filaActual)}" >
                      <td class="py-2 text-center">{{ rol.nombre }}</td>
                      <td class="py-2 text-center">
                         <div class="" v-if="rol.activo">
                            <svg class="w-6 h-6 m-auto text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         </div>
                         <div class="bg-red-500" v-else>
                            <svg class="w-6 h-6 m-auto text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                         </div>
                      </td>
                  </tr>
            </tbody>
        </table>
     </div>
</template>