<template>

        <section class="Categoires-top">
          <div class="container-cum pt-3 pb-5">
            <div class="title-top d-flex justify-content-end">
              <Link :href="route('categories')"> {{ __('See All') }} </Link>
            </div>
            <div class="row px-2 justify-content-between">
              <!-- <div class="col-12"> -->
              <div class="category-box" :class="{'medium-hidden':index>15,'mobile-hidden':index>17}" v-for="category,index in categories" :key="index">
                <category-circle-box :category="category"></category-circle-box>
              </div>
              <!-- </div> -->
            </div>
          </div>
        </section>
</template>


<script>
import CategoryCircleBox from '../Components/CategoryCircleBox.vue';

export default {

    components:{CategoryCircleBox},
    props:{
        categories:Array
    },
    mounted(){

    },

}

</script>
