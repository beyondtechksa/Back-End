<template>

    <Head :title="$page.props.page_title"/>
    <app>

        <main class="overflow-hidden shop-pages">
            <section class="shop_container">
                <div class="container-cum">
                    <div class="shop-content">
                        <div class="row">

                            <div class="col-xl-12 col-lg-12">
                                <div class="shop-right-container">
                                    <div class="shop-right-heading-content d-lg-block">
                                        <div class="shop-head-title mb-3">
                                            <h2>
                                                <span class="number mx-2"> {{ products.length }} </span>
                                                <span>{{ __('Results Listed') }}</span>
                                            </h2>
                                        </div>

                                    </div>
                                    <div class="text-center" v-if="brand.translated_image">
                                        <img class="collection-img" v-lazy="brand.translated_image">
                                    </div>
                                    <h1 class="text-center  fw-bold mb-3">
                                        {{ brand.translated_name }}
                                    </h1>
                                    <!-- ======   Shop Products   =====   -->
                                    <div class="row firstDiv">
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-6" v-for="product,index in products"
                                             :key="index">
                                            <product-box :product="product"></product-box>
                                        </div>

                                        <div  id="load-more-box" ref="loadMoreBox" class="loadMore">
                                            <!-- <div class="shop-serv loadMorebutton">
                                                <a @click="load_more()" href="javascript:void(0)">
                                                    {{ __('Load More') }} </a>
                                            </div> -->
                                        </div>
                                    </div>
                                    <!-- ======== Shop  Products  ======== -->
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
import ProductBox from './Components/ProductBox.vue';
import {useForm} from '@inertiajs/vue3';
import Form from 'vform'

export default {
    components: {App, ProductBox},
    props: {
        brand: Object,
        products: Array,
        page_type: String
    },
    mounted() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                this.load_more();
                }
            });
            });

            observer.observe(this.$refs.loadMoreBox);
    },
    beforeDestroy() {

    },
    data() {
        return {
            loading: false,
            limit: 12,
            form:new Form({
                'brand_id': this.brand.id,
                'limit': 12
            })
        }
    },
    methods: {

        load_more() {
            this.loading = true
            this.form.limit =parseInt(this.form.limit) + 12

                this.form.post('/load-more-products').then(res => {
                    this.products.splice(0, this.products.length);
                    res.data.forEach(item => {
                        this.products.push(item);
                    });
                }).catch(err => {
                    console.log(err)
                })

        },

    },

}

</script>
