<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
// import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

 const props = defineProps({
   camposValores:Object,
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
               <div> <!--Valores-->
                  <div v-for="valor in camposValores?.valors" :key="valor.id">
                    <div v-if="valor.campo_id == campo.campo_id">
                       <!--Reflejo dependiendo el tipo de campo-->
                       <div v-if="campo.tipo_campo == 'number' || campo.tipo_campo=='text'">
                          <h3>{{ valor.valor }}</h3>
                       </div>
                       <div v-if="campo.tipo_campo == 'image'">
                          <img :src="valor.valor" />
                       </div>
                    </div>
                  </div>
               </div>
             </div>
           </div>
       </div>
    </div>
</template>