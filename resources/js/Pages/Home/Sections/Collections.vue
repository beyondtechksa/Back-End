<template>
<section class="our_collection">
          <div class="container-cum">
            <div class="title-top d-flex justify-content-between">
              <h2>{{ setting.translated_key }}</h2>
              <Link :href="setting.link">{{__('See All')}}</Link>
            </div>
            
            <div class="our_collection_content">
              <div class="row">
                <div class="col-lg-6 col-md-6  gap-s-5 gap-e-5 gap-t-10" v-for="collection,index in setting.value" :key="index">
                    <our-collection-box :collection="collection"></our-collection-box>
                </div>
              </div>
            </div>
          </div>
        </section>
</template>


<script>
import OurCollectionBox from '../Components/OurCollectionBox.vue'

    export default {
  components: { OurCollectionBox },
        props:{setting:Object}
    }

</script>