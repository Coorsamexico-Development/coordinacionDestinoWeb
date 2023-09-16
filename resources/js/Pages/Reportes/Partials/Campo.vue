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
    <div>
       <div v-if="camposValores.campos"> <!--Recorrido de campos-->
           <div v-for="campo in camposValores.campos" :key="campo.campo_id">
             <div>
               <InputLabel>
                  {{ campo.campo }}
               </InputLabel>
               <div v-if="camposValores.valors.length > 0"> <!--Valores-->
                  <div v-for="valor in camposValores.valors" :key="valor.id">
                    <div v-if="valor.campo_id == campo.campo_id">
                       <!--Reflejo dependiendo el tipo de campo-->
                       <div v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                          <h3>{{ valor.valor }}</h3>
                       </div>
                       <div v-if="campo.tipo_campo == 'image'">
                          <img :src="valor.valor" data-fancybox />
                       </div>
                       <div v-if="campo.tipo_campo == 'file'">
                        <a :href="valor.valor" data-fancybox   data-type="pdf">
                           <ButtonWatch :color="'#1D96F1'" />
                        </a>
                       </div>
                       <div v-if="campo.tipo_campo == 'firma'" style="display: none;">
                       </div>
                    </div>
                  </div>
               </div>
               <div v-else>
                  <h3>Aun no hay información</h3>
               </div>
             </div>
           </div>
       </div>
    </div>
</template>