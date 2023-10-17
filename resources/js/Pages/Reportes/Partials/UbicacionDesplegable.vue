<script setup>
import axios from "axios";
import {ref, watch, computed, reactive } from "vue";

//Props
var props = defineProps({
    ubicacion:Object,
    plataformas:Object,
    status:Object,
    buscador:String
});
</script>
<template>
   <div class="bg-white rounded-xl drop-shadow-lg"> <!--main-->
     <div> <!--Header-->
        <div class="flex flex-row items-center justify-between p-4 mx-2 mt-4 bg-white rounded-lg">
          <h1 class="text-lg uppercase" style="font-family: 'Montserrat';">{{ ubicacion.nombre_ubicacion }}</h1>
        </div>
     </div>
   </div>
</template>