<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';
// import Swiper and modules styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

 const props = defineProps({
    campoValor:Object,
  });
</script>
<template>
    <div>
        <InputLabel>
            {{ campoValor.campo }}
        </InputLabel>
        <div v-if="campoValor.tipo_campo == 'image'">
           <img :src="campoValor.valor" />
        </div>
        <div v-if="campoValor.tipo_campo == 'text' || campoValor.tipo_campo == 'number'" >
           <h1>
              {{ campoValor.valor }}
           </h1>
        </div>
    </div>
</template>