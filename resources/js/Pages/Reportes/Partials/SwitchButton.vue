<script setup>
import {ref, watch, computed, reactive } from "vue";
import ContadorPlataforma from './ContadorPlataforma.vue'
const emit = defineEmits(["setPlataforma"])
//Props
var props = defineProps({
    plataformas:Object,
    ubicacion:Object,
    status:Object
});

let activo = ref(1)
const setearPlataforma = (plataforma_id) => 
{
  emit('setPlataforma', plataforma_id)
  activo.value = plataforma_id;
  
}

</script>
<template>
    <div class="flex flex-row justify-center py-4 overflow-x-auto">
        <div class="mx-0.5" v-for="plataforma in plataformas" :key="plataforma.id">
          <div @click="setearPlataforma(plataforma.id)">
            <div class="bg-[#C6C6C6] px-4 py-1 rounded-full text-white flex flex-row" v-if="activo === plataforma.id">
               <h1  class="mr-4"  style="font-family: 'Montserrat';">{{ plataforma.nombre }}</h1>
               <ContadorPlataforma :confirmaciones="plataforma.confirmaciones_dts" :ubicacion="ubicacion" :status="status" />
            </div>
            <div class="flex flex-row px-4 py-1 text-[#9B9B9B]" v-else>
              <h1 class="text-[#9B9B9B] mr-4"  style="font-family: 'Montserrat';">{{ plataforma.nombre }}</h1>
              <ContadorPlataforma :confirmaciones="plataforma.confirmaciones_dts" :ubicacion="ubicacion" :status="status" />
            </div>
          </div>
        </div>
    </div>
</template>