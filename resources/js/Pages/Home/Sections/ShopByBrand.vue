<template>
    <section class="shop_brand">
          <div class="container-cum pt-4 ">
            <div class="title-top d-flex justify-content-between">
              <h2>{{ setting.translated_key }}</h2>
              <Link v-if="setting.slug!='shop_by_section'" :href="route('shop_by_brand')">{{__('See All')}}</Link>
            </div>
            <div class="pt-2">
              <!-- <brand-box v-for="brand,index in " :key="index" :brand="brand"></brand-box> -->
              <swiper-slider :slider_data="setting.value" type="brands" shown="5"></swiper-slider>
            </div>
          </div>
        </section>
</template>


<script>
import SwiperSlider from '@/Components/SwiperSlider.vue'

export default {
  components: { SwiperSlider },
        props:{setting:Object}
    }

</script>