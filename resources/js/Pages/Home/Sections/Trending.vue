<template>
    <section class="Trending_Styles pt-5 pb-5">
          <div class="container-cum">
            <div class="title-top d-flex justify-content-between">
              <h2>{{__('Trending Styles')}}</h2>
                <Link :href="route('products.shop',{ product_type: 'trending'})"> {{ __('See All') }} </Link>

            </div>

            <div class="pt-4 pb-4">
            <swiper-slider :slider_data="products"></swiper-slider>
            </div>

          </div>
        </section>
</template>


<script>
import SwiperSlider from '@/Components/SwiperSlider.vue'

export default {
  components: {SwiperSlider },
        props:{products:Array}
    }

</script>
