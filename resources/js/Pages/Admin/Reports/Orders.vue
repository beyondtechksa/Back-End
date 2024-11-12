<template>

    <auth-layout>
        <Head title="Dashboard"/>
        <div class="container-fluid">
            <div class="row mt-4 mb-3">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Completed Orders</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{completed_orders}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Completed Orders %</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ completed_percentage }} %
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-secondary-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Opened Orders</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ open_orders }}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-danger-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Canceled Orders</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ canceled_orders}}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <!-- <BRow>
                <BCol xl="8">
                  <Revenue />
                </BCol>
            </BRow> -->

        </div>
    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
// import Revenue from "./revenue.vue";

import {Form} from 'vform'

export default {
    props: ['open_orders', 'canceled_orders', 'completed_orders', 'completed_percentage'],
    components: {AuthLayout, PageHeader,},
    data() {
        return {

            form: new Form({
                range: null
            })
        }
    }

}
</script>

