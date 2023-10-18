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
        pagination: {
          clickable: true,
          renderBullet: function (index, className) {
            return '<span class="' + className + '">' + (index + 1) + '</span>';
          },
        },
      };

    },
  };
</script>
<template>
    <swiper
    :modules="modules"
    :slides-per-view="1"
    :progressbarOpposite="true"
    :pagination="pagination"
    @swiper="onSwiper"
    @slideChange="onSlideChange"
  >
    <swiper-slide v-for="valor in valores" :key="valor.id" > 
        <div class="flex justify-center">
            <img class="rounded-md" :src="valor.valor" :href="valor.valor" data-fancybox />
        </div>
    </swiper-slide>
  </swiper>
</template>
<style >
.swiper {
  width: 25rem;
  height: 100%;
}

.swiper-slide {
  text-align: center;
  font-size: 18px;
  background: #fff;
  
  /* Center slide text vertically */
  display: flex;
  justify-content: center;
  align-items: center;
}

.swiper-slide img {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.swiper-pagination-bullet {
  width: 20px;
  height: 20px;
  text-align: center;
  line-height: 20px;
  font-size: 12px;
  color: #000;
  opacity: 1;
  background: white;
}

.swiper-pagination-bullet-active {
  color: #fff;
  background: #007aff;
}

</style>