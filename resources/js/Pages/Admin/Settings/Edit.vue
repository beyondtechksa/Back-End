<template>

    <auth-layout>
      <Head :title="__('profile')" />
      <div class="container">

        <page-header></page-header>
        <div v-if="setting.slug=='news_bar'">
            <news-bar :type="type" :setting="setting" :collections="collections"></news-bar>
        </div>
        <div v-if="setting.slug=='top_slider'">
            <top-slider :type="type" :setting="setting" :collections="collections"></top-slider>
        </div>
        <div v-if="setting.slug=='top_banner' || setting.slug=='banners2' || setting.slug=='small_banners'">
            <banners :type="type" :setting="setting" :collections="collections"></banners>
        </div>
        <div v-if="setting.slug=='latest_collection' || setting.slug=='our_collection'">
            <latest-collection :type="type" :setting="setting" :collections="collections"></latest-collection>
        </div>
        <div v-if="setting.slug=='single_banner' || setting.slug=='single_banner2'|| setting.slug=='single_banner3'|| setting.slug=='single_banner4'|| setting.slug=='single_banner5' || setting.slug=='single_banner6'">
            <single-banner :type="type" :setting="setting" :collections="collections"></single-banner>
        </div>
        <div v-if="setting.slug=='shop_by_brand'">
            <shop-by-brand :type="type" :setting="setting" :collections="collections"></shop-by-brand>
        </div>
        <div v-if="setting.slug=='shipping'">
            <Shipping :type="type" :setting="setting" :collections="collections"></Shipping>
        </div>
        <div v-if="setting.slug=='logo' || setting.slug=='favicon'">
            <Logo :type="type" :setting="setting" :collections="collections"></Logo>
        </div>
        <div v-if="setting.slug=='social'">
            <Social :type="type" :setting="setting" :collections="collections"></Social>
        </div>
        <div v-if="setting.slug=='popup'">
            <Popup :type="type" :setting="setting" :collections="collections"></Popup>
        </div>
        <div v-if="setting.slug=='banners3'">
            <banners3 :type="type" :setting="setting" :collections="collections"></banners3>
        </div>
      
        <div v-if="setting.slug=='shop_by_section'">
            <shop-by-section :type="type" :setting="setting" :collections="collections"></shop-by-section>
        </div>
        <div v-if="setting.slug=='vat'">
            <Vat :type="type" :setting="setting" :collections="collections"></Vat>
        </div>
        <div v-if="setting.slug=='category_banners'">
            <category-banners :type="type" :setting="setting" :collections="collections"></category-banners>
        </div>
      </div>


    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import NewsBar from './Components/NewsBar.vue';
import TopSlider from './Components/TopSlider.vue';
import Banners from './Components/Banners.vue';
import LatestCollection from './Components/LatestCollection.vue';
import SingleBanner from './Components/SingleBanner.vue';
import ShopByBrand from './Components/ShopByBrand.vue';
import ShopBySection from './Components/ShopBySection.vue';
import Banners3 from './Components/Banners3.vue';
import Shipping from './Components/Shipping.vue';
import Logo from './Components/Logo.vue';
import Social from './Components/Social.vue';
import Popup from './Components/Popup.vue';
import CategoryBanners from './Components/CategoryBanners.vue';
import Vat from "@/Pages/Admin/Settings/Components/Vat.vue";
export default {
  components: { AuthLayout, PageHeader, NewsBar, TopSlider, Banners, LatestCollection, SingleBanner, ShopByBrand, Shipping, Logo, Social, Popup, Banners3, ShopBySection , Vat, CategoryBanners },
  props:{
        setting:Object,
        collections:Array,
        type:String
    },
  data(){
        return {


        }
    },
    methods:{


    }

  }
</script>

