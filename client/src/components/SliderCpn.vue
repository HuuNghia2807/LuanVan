<template>
  <div class="slider">
    <swiper
      :slidesPerView="1"
      :spaceBetween="30"
      :centeredSlides="true"
      :navigation="true"
      :pagination="{
        clickable: true,
      }"
      :loop="true"
      :autoplay="{
        delay: 3000,
        disableOnInteraction: true,
      }"
      :modules="modules"
      class="mySwiper"
    >
      <swiper-slide v-for="image in listSlider" :key="image.key">
        <img :src="image.link" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// Import Swiper styles
import "swiper/css";

import "swiper/css/navigation";
import "swiper/css/pagination";

// import required modules
import { Autoplay, Navigation, Pagination } from "swiper";

export default defineComponent({
  components: {
    Swiper,
    SwiperSlide,
  },
  setup() {
    const listSlider = ref([
      {
        link: "https://kingshoes.vn/data/upload/media/banner-sn3web-1571294680--acbihpps-1592386128--hipspcba-1622453987.jpg",
        key: 1,
      },
      {
        link: "https://kingshoes.vn/data/upload/media/coverwebcrep-1554432453i-1622454041-1623428656.png",
        key: 2,
      },
      {
        link: "https://kingshoes.vn/data/upload/media/spa-giay-dan-sole-ve-sinh-crep-protect-tai-king-shoes-sneaker-real-hcm-gia-re-8-1576661620--phbspcia-1592386256--apbihspc-1622454065.jpg",
        key: 4,
      },
    ]);
    return {
      listSlider,
      modules: [Autoplay, Navigation, Pagination],
    };
  },
});
</script>

<style lang="scss" scoped>
.slider {
  margin-top: var(--height-header);
  height: calc(100vh - var(--height-header));

  .swiper {
    width: 100%;
    height: 100%;
  }

  .swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;

    /* Center slide text vertically */
    display: -webkit-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    -webkit-justify-content: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-align-items: center;
    align-items: center;
  }

  .swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .swiper {
    margin-left: auto;
    margin-right: auto;
  }
}
</style>
