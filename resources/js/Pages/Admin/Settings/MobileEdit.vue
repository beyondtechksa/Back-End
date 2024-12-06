<template>

    <auth-layout>
      <Head :title="__('profile')" />
      <div class="container">

        <page-header></page-header>
        <div v-if="slug=='top_slider' || slug=='small_banners'">
            <top-slider :slug="slug" :category_id="category_id" :mobile_banners="mobile_banners" :collections="collections" :categories="categories"></top-slider>
        </div>
        <div v-if="slug=='single_banner1' || slug=='single_banner2' || slug=='single_banner3' || slug=='banners1'">
            <single-banner :slug="slug" :category_id="category_id" :mobile_banners="mobile_banners" :collections="collections" :categories="categories"></single-banner>
        </div>
        <div v-if="slug=='shop_by_brand' || slug=='shop_by' || slug=='category_banners'|| slug=='search_banners'|| slug=='banners2'">
            <shop-by-brand  :slug="slug" :category_id="category_id" :mobile_banners="mobile_banners" :collections="collections" :categories="categories"></shop-by-brand>
        </div>
        <!-- <div v-if="slug=='top_banner' || slug=='banners2' || slug=='small_banners'">
            <banners :type="type" :setting="setting" :collections="collections"></banners>
        </div> 
        
        <div v-if="slug=='latest_collection' || slug=='our_collection'">
            <latest-collection :type="type" :setting="setting" :collections="collections"></latest-collection>
        </div>
        
        <div v-if="slug=='shop_by_brand'">
            <shop-by-brand :type="type" :setting="setting" :collections="collections"></shop-by-brand>
        </div>
        <div v-if="slug=='shipping'">
            <Shipping :type="type" :setting="setting" :collections="collections"></Shipping>
        </div>
        <div v-if="slug=='logo' || slug=='favicon'">
            <Logo :type="type" :setting="setting" :collections="collections"></Logo>
        </div>
        <div v-if="slug=='social'">
            <Social :type="type" :setting="setting" :collections="collections"></Social>
        </div>
        <div v-if="slug=='popup'">
            <Popup :type="type" :setting="setting" :collections="collections"></Popup>
        </div>
        <div v-if="slug=='banners3'">
            <banners3 :type="type" :setting="setting" :collections="collections"></banners3>
        </div>
      
        <div v-if="slug=='shop_by_section'">
            <shop-by-section :type="type" :setting="setting" :collections="collections"></shop-by-section>
        </div>
        <div v-if="slug=='vat'">
            <Vat :type="type" :setting="setting" :collections="collections"></Vat>
        </div>
        <div v-if="slug=='category_banners'">
            <category-banners :type="type" :setting="setting" :collections="collections"></category-banners>
        </div> -->
      </div>


    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import TopSlider from './MobileComponents/TopSlider.vue';
import SingleBanner from './MobileComponents/SingleBanner.vue';
import ShopByBrand from './MobileComponents/ShopByBrand.vue';

export default {
  components: { AuthLayout, PageHeader, TopSlider, SingleBanner, ShopByBrand },
  props:{
        mobile_banners:Object,
        collections:Array,
        categories:Array,
        slug:String,
        category_id:String
    },
  data(){
        return {


        }
    },
    methods:{


    }

  }
</script>

