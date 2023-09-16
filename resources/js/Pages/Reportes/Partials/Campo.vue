<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import ButtonWatch from '@/Components/ButtonWatch.vue';
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
import { Fancybox } from '@fancyapps/ui/dist/fancybox/fancybox.esm.js';
import '@fancyapps/ui/dist/fancybox/fancybox.css';
// import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
 const props = defineProps({
   camposValores:Object,
  });

  Fancybox.bind("[data-fancybox]", {
    // Your custom options
});
</script>
<template>
    <div v-for="campo in camposValores">
       <h1>{{ campo.campo }}</h1>
       <div v-for="(valor,key) in campo.valores" :key="valor.id">
         <div v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
              <h3>{{ valor.valor }}</h3>
         </div>
         <div v-if="key == 0">
           <div v-if="campo.tipo_campo == 'file'">
            <a :href="valor.valor" data-fancybox="gallery"    >
               <ButtonWatch :color="'#1D96F1'" />
            </a>
           </div>
         </div>
         <div v-if="key == 0">
           <div v-if="campo.tipo_campo == 'image'">
            <a :href="valor.valor" data-fancybox="gallery"    >
               <ButtonWatch :color="'#1D96F1'" />
            </a>
           </div>
         </div>
       </div>
    </div>
</template>