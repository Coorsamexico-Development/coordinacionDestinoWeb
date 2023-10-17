<script setup>
//Importaciones
import {ref, watch, computed } from "vue";
import { Link, useForm } from '@inertiajs/vue3'
import ButtonDropZone from '@/Components/ButtonDropZone.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import UbicacionDesplegable from './UbicacionDesplegable.vue';

//Props
var props = defineProps({
    statu:Object,
    ubicaciones:Object,
    plataformas:Object,
    contadores:Object,
    buscador:String
});

</script>
<template>
   <div class="flex flex-row justify-between">
      <h1 class="text-lg" style="font-family: 'Montserrat';">{{ statu.nombre }}</h1>
      <div v-if="statu.id == 1">
           <a :href="route('downloadReport')"  class="px-2 border rounded-lg">
              <span style="font-family: 'Montserrat';">Descargar ejemplo</span>
           </a>
        </div>
   </div>
</template>