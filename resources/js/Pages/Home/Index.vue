

<template>

    <Head :title="$page.props.page_title" />

      <app>
        <main class="overflow-hidden">
            <div class="header-top-title p-1">
              <marquee direction="right" class="text-white text-center">
                  <h4 v-if="get_news_bar_settings()" class="marque-text"> {{ get_news_bar_settings().translated_key }} </h4>
              </marquee>
          </div>
        <!-- Hero area -->
        <div class="hero_area" v-show="get_slider_setting().status==1">
            <div class="swiper heroSider">
              <div class="swiper-wrapper">
                <div class="swiper-slide hero_slider" v-for="slider,index in get_slider_setting().value" :key="index">
                 <Link :href="slider.link">
                   <img class="mobile-hidden" v-lazy="slider[$page.props.locale]" alt="" />
                   <img class="web-hidden" v-lazy="slider['mobile_'+$page.props.locale]" alt="" />
                  </Link>
                </div>
              </div>
              <div class="hero_slider_arrow">
                <button class="hero_slider_prev">
                  <img src="/home/img/chevron-circle-left.svg" alt="">
                </button>
                <button class="hero_slider_next">
                  <img src="/home/img/chevron-circle-right.svg" alt="">
                </button>
              </div>
            </div>
        </div>
        <!-- ======  Banner Section  ====  -->

      <!-- --====  Categoires   ======== -->
      <top-categories :categories="top_categories"></top-categories>

        <section class="brand-section-h">
          <div class="container-cum banner-hero-area">
            <div class="row banner-hero-content mobile-hidden" v-show="get_top_banner().status==1">
              <div class="col-lg-6 col-md-12 gap-s-5 gap-e-5">
                <div class=""  v-for="banner,index in get_top_banner_setting('left')" :key="index">
                  <Link :href="banner.link" class="d-block">
                    <img style="height:370px" v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>
              </div>
              <div class="col-lg-6 col-md-12 gap-s-5 gap-e-5 right-content">
                <div :class="{'gap-t-10':index>0}" v-for="banner,index in get_top_banner_setting('right')" :key="index">
                  <Link :href="banner.link" class="d-block">
                    <img v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>

              </div>
            </div>
            <div class="row banner-hero-content web-hidden"  v-show="get_top_banner().status==1">
              <div class="col-12 mb-2">
                <div class=""  v-for="banner,index in get_top_banner_setting('left')" :key="index">
                  <Link :href="banner.link" class="d-block">
                    <img style="height:370px" v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>
              </div>
              <div class="col-12 mb-2 right-content">
                <div :class="{'gap-t-10':index>0}" v-for="banner,index in get_top_banner_setting('right')" :key="index">
                  <Link :href="banner.link" class="d-block">
                    <img v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>

              </div>
            </div>
            <div v-show="get_brand_setting().status==1">
            <shop-by-brand :setting="get_brand_setting()"></shop-by-brand>
            </div>
            <div class="row mobile-hidden"  v-show="get_top_banner().status==1">
              <div class="col-lg-3 col-md-6 gap-s-5 gap-e-5  gap-t-10" v-for="banner,index in get_top_banner_setting('bottom')" :key="index">
                <div class="fblk" >
                  <Link :href="banner.link" class="d-block">
                    <img v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>
              </div>
            </div>
            <div class="web-hidden">
                <swiper-slider type="banners" :slider_data="get_top_banner_setting('bottom')"></swiper-slider>


            </div>
          </div>
        </section>
        <div  v-show="get_single_banner6_setting().status==1">
        <banner  :heightt="'270px'" class="mt-4" :setting="get_single_banner6_setting()" />
        </div>

        <section class="my-4" v-show="get_small_banners_setting().status==1">
          <div class="container-cum banner-hero-area">
            <div class="row mobile-hidden">
              <div class="col-xl-2 col-lg-3 gap-s-5 gap-e-5 gap-t-10 " v-for="banner,index in get_small_banners_setting().value" :key="index">
                <div class="fblk" >
                  <Link :href="banner.link" class="d-block">
                    <img style="height:120px;width:100%" v-lazy="banner.image[$page.props.locale]" alt="" />
                  </Link>
                </div>
              </div>
            </div>
            <div class="web-hidden">
              <div>
                <swiper-slider type="banners" :slider_data="get_small_banners_setting().value"> </swiper-slider>
              </div>
            </div>

          </div>
        </section>

        <!-- ======  Banner Section  ====  -->



        <!-- ====== Collection  ==== -->
        <latest-collection v-show="get_latest_collection_setting().status==1" :setting="get_latest_collection_setting()"></latest-collection>

        <!-- ======= Collection ====== -->


        <!-- ===== Treading Style  ===== -->
        <trending :products="trending"></trending>

        <!-- ====== Treading style End  ==== -->

        <!-- ===== Banner ==== -->
         <div  v-show="get_single_banner_setting(0).status==1">
        <banner :heightt="'218px'" :setting="get_single_banner_setting(0)" />
        </div>

        <!-- ====  Banner=== -->

        <!-- =====  Shop By Brand ===== -->

        <!-- =====  Shop By Brand End   ===== -->

        <!-- =======  Sele Brand Banner ====== -->

        <section class="sale-brand"  v-show="get_banners2_setting().status==1">
          <div class="container-cum pt-4 pb-4">
            <div class="row">
              <div class="col-lg-2 col-md-4 col-4 gap-s-5 gap-e-5" v-for="banner,index in get_banners2_setting().value" :key="index">
                <Link :href="banner.link">
                  <div class="sale-branner">
                    <img v-lazy="banner.image[$page.props.locale]" alt="" />
                  </div>
                </Link>
              </div>

            </div>
          </div>
        </section>
        <!-- =======  Sele Brand Banner ====== End -->
        <!-- ====   Featured Products ====   -->
        <featured :products="featured"></featured>
        <!-- ===== Featured Products End  ==== -->

        <!-- ===== Banner ==== -->
         <div   v-show="get_single_banner2_setting().status==1">
        <banner   :setting="get_single_banner2_setting()" />
        </div>
        <!-- ====  Banner=== -->
        <!-- ==== Our  Collections ===== -->
        <collections :setting="get_our_collection_setting()"></collections>

        <!-- ====== Our Collections ===== -->

        <!-- ===== Banner ==== -->
        <div   v-show="get_single_banner3_setting().status==1">
        <banner  :setting="get_single_banner3_setting()" />
        </div>
        <!-- ====  Banner=== -->

        <!--   ========= New Arrivals in Home Fornitures  ==== -->

        <new-arrival :products="new_arrival"></new-arrival>
        <!-- ======= New Arrivals in Home Fornitures ===== -->

        <!-- ===== Banner ==== -->
        <div   v-show="get_single_banner4_setting().status==1">
        <banner   :setting="get_single_banner4_setting()" />
        </div>
        <!-- ====  Banner=== -->

        <!-- ===== Shop by===== -->
        <div   v-show="get_shop_by_section_setting().status==1">
        <shop-by-brand :setting="get_shop_by_section_setting()"></shop-by-brand>
        </div>
        <!-- ====== Shop by ==== -->

        <!-- ===== Banner ==== -->
        <div   v-show="get_single_banner5_setting().status==1">
        <banner  :setting="get_single_banner5_setting()" />
        </div>
        <!-- ====  Banner=== -->

        <section class="lust-banner-end" v-show="get_banners3_setting().status==1">
          <div class="container-cum pt-4 pb-4">
            <div class="row" style="padding: 0 30px;">
              <div class="col-lg-6 col-md-6 col-6 gap-s-5 gap-e-5" v-for="val,index in get_banners3_setting().value" :key="index">
                <div class="bannner_box bg-overlay-left">
                  <img class="mobile-hidden" style="height:563px" v-lazy="val['image']" alt="" />
                  <img class="web-hidden" style="height:200px" v-lazy="val['image']" alt="" />
                  <div class="banner-by-title">
                    <h4>{{val['name'][$page.props.locale]}}</h4>
                    <a href="#"> {{ __('Shop Now') }} </a>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </section>

      </main>
      </app>

  </template>



  <script>
  import App from '@/HomeLayouts/AppLayout.vue';
  import TopCategories from './Sections/TopCategories.vue';
  import LatestCollection from './Sections/LatestCollection.vue';
  import Trending from './Sections/Trending.vue';
  import ShopByBrand from './Sections/ShopByBrand.vue';
  import Featured from './Sections/Featured.vue';
  import Collections from './Sections/Collections.vue';
  import NewArrival from './Sections/NewArrival.vue';
import Banner from './Components/Banner.vue';
import SwiperSlider from '@/Components/SwiperSlider.vue';

  export default {
      components: { App, TopCategories, LatestCollection, Trending, ShopByBrand, Featured, Collections, NewArrival, Banner, SwiperSlider},
      props:{

        top_categories:Array,
        parent_categories:Array,
        latest_collections:Array,
        trending:Array,
        brands:Array,
        featured:Array,
        collections:Array,
        new_arrival:Array,
      },
      data(){
        return {
          settings:this.$page.props.settings
        }
      },
      methods:{
        get_slider_setting(){
          return this.settings.find((e)=>e.slug=='top_slider')
        },
        get_top_banner(){
          let  settings = this.settings.find((e)=>e.slug=='top_banner')
          return settings
        },
        get_top_banner_setting(position){
          let returned = []
          let  settings = this.settings.find((e)=>e.slug=='top_banner')
          if(settings.value){
            settings.value.forEach((element,index) => {
              if(position=='left' && index==0){
                  returned.push(element)
              }else if(position=='right' && (index == 1 || index == 2)){
                  returned.push(element)
              }else if(position=='bottom' && index >2){
                  returned.push(element)
              }
            });
          }
          return returned
        },
        get_latest_collection_setting(){
          return this.settings.find((e)=>e.slug=='latest_collection')
        },
        get_our_collection_setting(){
          return this.settings.find((e)=>e.slug=='our_collection')
        },
        get_single_banner_setting(){
          return this.settings.find((e)=>e.slug=='single_banner')
        },
        get_single_banner2_setting(){
          return this.settings.find((e)=>e.slug=='single_banner2')
        },
        get_single_banner3_setting(){
          return this.settings.find((e)=>e.slug=='single_banner3')
        },
        get_single_banner4_setting(){
          return this.settings.find((e)=>e.slug=='single_banner4')
        },
        get_single_banner5_setting(){
          return this.settings.find((e)=>e.slug=='single_banner5')
        },
        get_single_banner6_setting(){
          return this.settings.find((e)=>e.slug=='single_banner6')
        },
        get_brand_setting(){
          return this.settings.find((e)=>e.slug=='shop_by_brand')
        },
        get_shop_by_section_setting(){
          return this.settings.find((e)=>e.slug=='shop_by_section')
        },
        get_banners2_setting(){
          return this.settings.find((e)=>e.slug=='banners2')
        },
        get_banners3_setting(){
          return this.settings.find((e)=>e.slug=='banners3')
        },
        get_news_bar_settings(){
          return this.settings.find((e)=>e.slug=='news_bar')
        },
        get_small_banners_setting(){
          return this.settings.find((e)=>e.slug=='small_banners')
        },
      }
  }

  </script>


  <style scoped>
.fblk {height: 97%;}

.fblk a {
    height: 100%;
}
  </style>
