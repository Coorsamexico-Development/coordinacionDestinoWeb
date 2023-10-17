<script setup>
 import {ref, watch, computed, reactive } from "vue";
 import ButtonWatch from '@/Components/ButtonWatch.vue'
 import ModalWatchHistoricoStatus from '../Modals/ModalWatchHistoricoStatus.vue';
 import ModalAddOcs from "../Modals/ModalAddOcs.vue";
 import axios from "axios";
  //Props
 var props = defineProps({
      dt:Object,
  });
</script>
<template>
 <div class="grid grid-cols-12 m-3 border rounded-lg drop-shadow-2xl" >
    <div class="w-4 col-start-1 mr-4 rounded-s-lg" :style="{backgroundColor:dt.color}">
    </div>
    <div class="col-start-2 col-end-7 py-2">
      <div class="flex flex-row my-1">
         <h1 class="text-xl font-semibold uppercase">DT: </h1>
         <p class="text-xl ">{{ dt.referencia_dt }} </p>
       </div>
       <div class="flex flex-row my-1"> 
          <h1 class="text-sm font-semibold uppercase">Conf: </h1>
          <p class="text-sm ">{{ dt.confirmacion }}</p>
       </div>
       <div class="flex flex-row my-1"> 
          <h1 class="text-xs font-bold uppercase">LT: </h1>
          <p class="text-xs">{{ dt.linea_transporte }}</p>
       </div>
    </div>
  </div>
</template>