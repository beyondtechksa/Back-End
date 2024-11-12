<template>
    <div>
        <swiper class="swiper responsive-swiper rounded gallery-light pb-4" :loop="true" :slidesPerView="1"
              :spaceBetween="10" :pagination="{ el: '.swiper-pagination', clickable: true }" :modules="[Pagination,Autoplay]" :autoplay="{ delay: 2500, disableOnInteraction: false }"
              :breakpoints="{ 360: { slidesPerView: 2, spaceBetween: 10 }, 640: { slidesPerView: 2, spaceBetween: 10 }, 768: { slidesPerView: 4, spaceBetween: 10 }, 1200: { slidesPerView: 5, spaceBetween: 10 }, }">
              <swiper-slide v-for="swiper_data,index in slider_data" :key="index">
                <brand-box v-if="type=='brands'" :brand="swiper_data"></brand-box>
                <div v-else-if="type=='banners'">
                  <div class="fblk" >
                  <Link :href="swiper_data.link" class="d-block">
                    <img v-lazy="swiper_data.image[$page.props.locale]" alt="" />
                  </Link>
                </div>
                </div>

                <product-box v-else :product="swiper_data"></product-box>
              </swiper-slide>
              <div class="swiper-pagination swiper-pagination-dark"></div>
            </swiper>
    </div>
</template>


<script>
import { Autoplay, Thumbs, Pagination, Navigation, EffectCoverflow, Mousewheel, Scrollbar, EffectFade, EffectFlip, EffectCreative } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/autoplay";
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/scrollbar';
import 'swiper/css/mousewheel';
import 'swiper/css/effect-fade';
import 'swiper/css/effect-creative';
import 'swiper/css/effect-flip';
import 'swiper/css/effect-coverflow';
import ProductBox from '@/Pages/Home/Components/ProductBox.vue';
import BrandBox from '@/Pages/Home/Components/BrandBox.vue';

export default {
    props:{
        slider_data:{
            type:Array,
            default:[]
        },
        type:{
            type:String,
            default:null
        },
        shown:{
            type:String,
            default:3
        },
    },
    data() {
    return {
      Autoplay: Autoplay, Thumbs: Thumbs, Pagination: Pagination, Navigation: Navigation, EffectCreative: EffectCreative,
      coverflowModules: [EffectCoverflow, Pagination],
      mousewheelModules: [Mousewheel, Pagination],
      scrollbarModules: [Scrollbar, Navigation, Pagination],
      effectFadeModules: [EffectFade, Pagination],
      effectFlipModules: [EffectFlip, Pagination],

      pagination: {
        clickable: true,
        el: '.swiper-pagination',
        renderBullet: function (index, className) {
          return '<span class=' + className + '>' + (index + 1) + '</span>';
        },
      },
    };
  },
  components: {
    Swiper,
    SwiperSlide,
    ProductBox,
    BrandBox,
  },
}

</script>
