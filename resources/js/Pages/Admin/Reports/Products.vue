<template>

    <auth-layout>
      <Head title="Dashboard" />
      <div class="container-fluid">
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"> Products Performance </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" v-if="productsAnalysis.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('sales')}}</th>
                                    <th scope="col">{{__('revenues')}}</th>
                                    <th scope="col">{{__('refunds')}}</th>
                                    <th scope="col">{{__('visitors')}}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in productsAnalysis.data" :key="index">
                                    <td>
                                        <div class="hover-image">
                                        <img width="60" v-if="product.image" v-lazy="product.image" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>
                                    <td>{{ substr(product.name_en+' / '+product.name_ar,40)}}</td>
                                    <td>{{ product.total_sell }}</td>
                                    <td>{{ product.total_revenue }}</td>
                                    <td>{{ product.refund_count }}</td>
                                    <td>{{ product.visit }}</td>



                                </tr>

                            </tbody>

                        </table>

                    </div>
                    <div class="pagination  mx-2 px-2">
                            <Pagination :links="productsAnalysis.links" />
                        </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"> Products Stock </h5>
            </div>
            <div class="card-body">
                <div class="btn-list mb-2">
                    <button class="btn btn-primary-transparent"> Finished Products </button>
                    <button class="btn btn-primary"> Need Request Products </button>
                </div>
                <div class="table-responsive" v-if="finished.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('sku')}}</th>
                                    <th scope="col">{{__('Stock')}}</th>


                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in finished.data" :key="index">

                                    <td>
                                        <div class="hover-image">
                                        <img width="60" v-if="product.image" v-lazy="product.image" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>
                                    <td>{{ substr(product.name_en+' / '+product.name_ar,40)}}</td>
                                    <td> {{ product.sku }} </td>
                                    <td> {{ product.stock_status }} </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>
                    <div class="pagination  mx-2 px-2">
                            <Pagination :links="finished.links" />
                    </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title"> Most visititng products (100) </h5>
            </div>
            <div class="card-body">

                <div class="table-responsive" v-if="visits.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('visitors')}}</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in visits.data" :key="index">

                                    <td>
                                        <div class="hover-image">
                                        <img width="60" v-if="product.image" v-lazy="product.image" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>
                                    <td>{{ substr(product.name_en+' / '+product.name_ar,40)}}</td>
                                    <td> {{product.visit}} </td>


                                </tr>

                            </tbody>

                        </table>

                    </div>
                    <div class="pagination  mx-2 px-2">
                            <Pagination :links="visits.links" />
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

import { Form } from 'vform'
export default {
  props:{
      productsAnalysis:Object,finished:Object,visits:Object
  },
  components: { AuthLayout, PageHeader, Pagination  },
  data(){
    return {

        form:new Form({
            range:null
        })
    }
  }

}
</script>

