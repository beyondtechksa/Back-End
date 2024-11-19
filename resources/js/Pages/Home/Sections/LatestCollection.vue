<template>
    <section class="collection-inner">
          <div class="container-cum mt-4">
            <div class="title-top d-flex justify-content-between">
              <h2> {{ setting.translated_key}} </h2>
              <Link :href="setting.link??''"> {{ __('See All') }} </Link>
            </div>
            <div class="collection-content pt-2" v-if="setting.value.length>0">
              <div class="row">
                <div class="col-lg-6 col-md-6 gap-s-5 gap-e-5">
                  <collection-box heightt="830" :collection="setting.value[0]"></collection-box>
                </div>
                <div class="col-lg-6 col-md-6 gap-s-5 gap-e-5">
                  <collection-box :class="{'gap-t-10':index>1}" heightt="270" classs="collection-right-box" :collection="collection" v-show="index>0"  v-for="collection,index in setting.value" :key="index"></collection-box>
                </div>
              </div>
            </div>
          </div>
        </section>
</template>


<script>
import CollectionBox from '../Components/CollectionBox.vue'

    export default {
  components: { CollectionBox },
        props:{setting:Object}
    }

</script>
