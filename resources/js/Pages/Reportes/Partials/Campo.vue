<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import ButtonWatch from '@/Components/ButtonWatch.vue';
import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
import Carrusel from '../Partials/Carrusel.vue'


 const props = defineProps({
   camposValores:Object,
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
});
</script>
<template>
    <div class="mt-2" v-for="campo in camposValores" :key="campo.id">
       <h1 class="text-lg" style="font-family: 'Montserrat';"> - {{ campo.campo }}</h1>
       <div v-for="(valor,key) in campo.valores" :key="valor.id">
         <div v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
              <h3 style="font-family: 'Montserrat';">
                {{ valor.valor }}
              </h3>
         </div>
         <div v-if="key == 0">
           <div v-if="campo.tipo_campo == 'file'">
            <a :href="valor.valor" data-fancybox    >
               <ButtonWatch :color="'#1D96F1'" />
            </a>
           </div>
         </div>
       </div>
       <!--Carruseles-->
       <div class="" v-if="campo.tipo_campo == 'image'">
          <Carrusel :valores="campo.valores" />
      </div>
    </div>
</template>