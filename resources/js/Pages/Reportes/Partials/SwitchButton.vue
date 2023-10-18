<script setup>
import {ref, watch, computed, reactive } from "vue";
const emit = defineEmits(["setPlataforma"])
//Props
var props = defineProps({
    plataformas:Object
});

let activo = ref(1)
const setearPlataforma = (plataforma_id) => 
{
  emit('setPlataforma', plataforma_id)
  activo.value = plataforma_id;
  
}


</script>
<template>
    <div class="flex flex-row justify-center py-4">
        <div class="mx-0.5" v-for="plataforma in plataformas" :key="plataforma.id">
          <div @click="setearPlataforma(plataforma.id)">
            <div class="bg-[#C6C6C6] px-4 py-1 rounded-full text-white" v-if="activo === plataforma.id">
               <h1 :class="[]"  style="font-family: 'Montserrat';">{{ plataforma.nombre }}</h1>
            </div>
            <div class="px-4 py-1" v-else>
              <h1 class="text-[#9B9B9B]"  style="font-family: 'Montserrat';">{{ plataforma.nombre }}</h1>
            </div>
          </div>
        </div>
    </div>
</template>