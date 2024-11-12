<template>

    <Head :title="$page.props.page_title"/>
    <app>

        <main class="overflow-hidden shop-pages">
            <section class="shop_container">
                <div class="container-cum">
                    <div class="row">
                        <div class="col-md-3 mb-3" v-for="brand,index in setting.value" :key="index">
                            <brand-box :brand="brand"></brand-box>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </app>

</template>


<script>
import App from '@/HomeLayouts/AppLayout.vue';
import BrandBox from './Components/BrandBox.vue';

export default {
    components: {App, BrandBox},
    props: {
        setting: Object,

    },
    
    data() {
        return {
            loading:false
        }
    },
    methods: {
     
    },

}

</script>


