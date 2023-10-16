<script >
  import { Navigation, Pagination, Scrollbar, A11y, Autoplay } from 'swiper/modules';
 // Import Swiper Vue.js components
 import { Swiper, SwiperSlide } from 'swiper/vue';
  // Import Swiper styles
  import 'swiper/css';
  import 'swiper/css/navigation';
  import 'swiper/css/pagination';
  import 'swiper/css/scrollbar';
  import 'swiper/css/autoplay';

  export default {
    components: {
      Swiper,
      SwiperSlide,
    },
    props: {
       valores:Object
     },
    setup() {
      const onSwiper = (swiper) => {
        //console.log(swiper);
      };
      const onSlideChange = () => {
        //console.log('slide change');
      };
      return {
        onSwiper,
        onSlideChange,
        modules: [Navigation, Pagination, Scrollbar, A11y, Autoplay],
      };
    },
  };
</script>
<template>
    <swiper
    :modules="modules"
    :slides-per-view="1"
    :space-between="50"
    :pagination="{ clickable: true }"
    :autoplay='{
               "delay": 6000,
              "disableOnInteraction": false
     }'
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
    <swiper-slide v-for="valor in valores" :key="valor.id" > 
        <div class="flex justify-center">
            <img class="w-24 rounded-md" :src="valor.valor" :href="valor.valor" data-fancybox />
        </div>
    </swiper-slide>
  </swiper>
</template>