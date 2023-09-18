<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import ButtonWatch from '@/Components/ButtonWatch.vue';
import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import Carrusel from './Carrusel.vue';
 const props = defineProps({
   camposValores:Object,
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
});



</script>
<template>
    <div v-for="campo in camposValores">
       <h1 class="text-lg" style="font-family: 'Montserrat';">{{ campo.campo }}</h1>
       <div v-if="campo.tipo_campo == 'file'">
          <Carrusel />
       </div>
       <div v-for="(valor,key) in campo.valores" :key="valor.id">
         <div v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
              <h3 style="font-family: 'Montserrat';">{{ valor.valor }}</h3>
         </div>
       </div>
       <!--Tipos de campo texto-->
    </div>
</template>