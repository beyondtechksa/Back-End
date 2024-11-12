<template>

    <auth-layout>
        <Head title="Dashboard"/>
        <div class="container-fluid">
            <div class="row justify-content-center mt-4 mb-3">
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
                                    <div class="mb-2">Agerage Cart Price</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{avgCarts}}
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
                                    <div class="mb-2">Cart leaving percentage</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ ignoredPercentage }}%
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Users Cart </h5>
                </div>
                <div class="card-body">

                    <div class="table-responsive" v-if="carts.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('image') }}</th>
                                <th scope="col">{{ __('product name') }}</th>
                                <th scope="col">{{ __('user name') }}</th>
                                <th scope="col">{{ __('visitors') }}</th>
                                <th scope="col">{{ __('total quantity') }}</th>
                                <th scope="col">{{ __('total') }}</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="cart,index in carts.data" :key="index">

                                <td>
                                    <div class="hover-image">
                                        <img width="60" v-if="cart.image" v-lazy="cart.image"
                                             class="avatar-sm img-thumbnail admin-profile-image"
                                             alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img"
                                             class="avatar-sm img-thumbnail admin-profile-image"
                                             alt="admin-profile-image">
                                    </div>
                                </td>
                                <td>{{ substr(cart.name_en + ' / ' + cart.name_ar, 40) }}</td>
                                <td>{{ cart.user_name }}</td>
                                <td> {{ cart.visit }}</td>
                                <td> {{ cart.totalQTY }}</td>
                                <td> {{ cart.total }}</td>


                            </tr>

                            </tbody>

                        </table>

                    </div>
                    <div class="pagination  mx-2 px-2">
                        <Pagination :links="carts.links"/>
                    </div>
                </div>
            </div>

        </div>
    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
// import Revenue from "./revenue.vue";
import Pagination from '@/Components/Pagination.vue';

import {Form} from 'vform'

export default {
    props: {
        carts: Object, avgCarts: String, ignoredPercentage: String
    },
    components: {AuthLayout, PageHeader, Pagination},
    data() {
        return {

            form: new Form({
                range: null
            })
        }
    }

}
</script>

