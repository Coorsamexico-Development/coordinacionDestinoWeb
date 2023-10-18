<script setup>
//Importaciones
import {ref, watch, computed } from "vue";
import { Link, useForm } from '@inertiajs/vue3'
import ButtonDropZone from '@/Components/ButtonDropZone.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import UbicacionDesplegable from './UbicacionDeplegable2.vue';
//Props
var props = defineProps({
    statu:Object,
    ubicaciones:Object,
    plataformas:Object,
    buscador:String
});
</script>
<template>
    <div>
       <!--header-->
       <div class="flex flex-row justify-between">
          <h1 class="text-lg" style="font-family: 'Montserrat';">{{ statu.nombre }}</h1>
       </div>
       <!--Body-->
       <div class="px-4 py-4 rounded-lg snap-2 h-96" style="overflow-y: scroll;">
          <div v-for="ubicacion in ubicaciones" :key="ubicacion.id">
            <!--
             <UbicacionDesplegable :buscador="buscador" :ubicacion="ubicacion" :status="statu" :plataformas="plataformas" />
             -->
          </div>
       </div>
    </div>
</template>