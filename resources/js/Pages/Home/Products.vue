<template>

    <Head :title="$page.props.page_title"/>
    <app>

        <main class="overflow-hidden shop-pages">
            <section class="shop_container">
                <div class="container-cum">
                    <div class="shop-content">
                        <div class="row">
                            <!--                            <div class="col-xl-3 col-lg-0">-->
                            <!--                                <div class="shop-left-container">-->
                            <!--                                    &lt;!&ndash; =======  Filter Shop  ====== &ndash;&gt;-->
                            <!--                                </div>-->
                            <!--                                &lt;!&ndash; =======  Mobile Response Filter  ========= &ndash;&gt;-->
                            <!--                            </div>-->
                            <div class="col-xl-12 col-lg-12">
                                <div class="shop-right-container" ref="targetDiv">
                                    <div class="shop-right-heading-content d-lg-block">
                                        <div class="shop-head-title">
                                            <h2>
                                                <span class="number"> {{ products.length }} </span>
                                                <span>{{ __('Results Listed') }}</span>
                                            </h2>
                                        </div>

                                    </div>
                                    <!-- ======   Shop Products   =====   -->
                                    <div class="row ">
                                        <div class="col-xl-3 col-lg-4 col-md-6 col-6" v-for="product,index in products"
                                             :key="index">
                                            <product-box :product="product"></product-box>
                                        </div>

                                        <div id="load-more-box" ref="loadMoreBox"  class="loadMore">
                                            <!-- <divc class="shop-serv loadMorebutton">
                                                <a @click="load_more()" href="javascript:void(0)">
                                                    {{ __('Load More') }} </a>
                                            </divc> -->
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

export default {
    components: {App, ProductBox},
    props: {
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
        window.removeEventListener('scroll', this.handleScroll);
    },
    data() {
        return {
            loading: false,
            limit: 9,
        }
    },
    methods: {
        filter() {
            this.form.get('shop', {preserveState: false, preserveScroll: true})
        },
        load_more() {
            this.loading = true
            this.limit += 9
            setTimeout((e) => {
                axios.post('/load-more-products',
                    {'type': this.page_type, 'limit': this.limit}).then(res => {
                    this.products.splice(0, this.products.length);
                    res.data.forEach(item => {
                        this.products.push(item);
                    });
                }).catch(err => {
                    console.log(err)
                })
            }, 2000)
        },
        handleScroll() {
            const targetDiv = this.$refs.targetDiv;
            const rect = targetDiv.getBoundingClientRect();
            const isAtBottom = rect.bottom <= window.innerHeight;
            const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

            if (isAtBottom && currentScrollTop > this.lastScrollTop) {
                if (!this.loading) {
                    setTimeout(() => {
                        this.load_more();
                    }, 1000);
                }
            }
            this.lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop; // For Mobile or negative scrolling
        },
    },

}

</script>


