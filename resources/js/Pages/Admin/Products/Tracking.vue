<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header py-1">
                        <div class="d-flex w-100">


                        </div>
                    </div>
                    <div class="table-responsive" v-if="traking_products.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('sku')}}</th>
                                    <th scope="col">{{__('product company')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('price')}}</th>
                                    <th scope="col">{{__('old price')}}</th>
                                    <th scope="col">{{__('discount price')}}</th>
                                    <th scope="col">{{__('old discount price')}}</th>
                                    <th scope="col">{{__('final price')}}</th>
                                    <th scope="col">{{__('old final price')}}</th>
                                    <th scope="col">{{__('final selling price')}}</th>
                                    <th scope="col">{{__('old final selling price')}}</th>
                                    <th scope="col">{{__('sizes')}}</th>
                                    <th scope="col">{{__('status')}}</th>
                                    <th scope="col">{{__('updated at')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in traking_products.data" :key="index">

                                    <td>
                                        <div class="hover-image">
                                        <img width="60" v-if="product.product.image" v-lazy="product.product.image" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>

                                    <td>{{product.product.sku}}</td>
                                    <td>{{product.product.company_name}}</td>
                                    <td>{{product.product.name_ar}} / {{ product.product.name_en }}</td>
                                    <td>
                                        <span v-if="product.old_price != product.price" class="text-success"> {{product.price}} </span>
                                        <span v-else class="text-primary"> {{product.price}} </span>
                                    </td>
                                    <td>
                                       <span v-if="product.old_price != product.price" class="text-danger"> {{product.old_price}} </span>
                                       <span v-else class="text-primary"> {{product.old_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_discount_price != product.discount_price" class="text-success"> {{product.discount_price}} </span>
                                        <span v-else class="text-primary"> {{product.discount_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_discount_price != product.discount_price" class="text-danger"> {{product.old_discount_price}} </span>
                                       <span v-else class="text-primary"> {{product.old_discount_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_final_price != product.final_price" class="text-success"> {{product.final_price}} </span>
                                        <span v-else class="text-primary"> {{product.final_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_final_price != product.final_price" class="text-danger"> {{product.old_final_price}} </span>
                                       <span v-else class="text-primary"> {{product.old_final_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_final_selling_price != product.final_selling_price" class="text-danger"> {{product.final_selling_price}} </span>
                                       <span v-else class="text-primary"> {{product.final_selling_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.old_final_selling_price != product.final_selling_price" class="text-danger"> {{product.old_final_selling_price}} </span>
                                       <span v-else class="text-primary"> {{product.old_final_selling_price}} </span>
                                    </td>
                                    <td>
                                        <span v-if="product.sizes_ids">
                                            <span v-if="product.sizes_ids.length>0">
                                                <span>
                                        <span class="fw-bold" v-for="option,index in product.sizes_ids" :key="index"><span v-if="index>0"> / </span>( {{   getSizeName(sizes,option.id)  }}
                                        -
                                        <span v-if="option.inStock" class="text-success"> In stock </span>
                                        <span v-else class="text-danger"> Out Of Stock </span>
                                         )</span>
                                        </span>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                        <span v-if="product.product.status==0" class="badge bg-warning-transparent"> {{ __('not active') }} </span>
                                        <span v-else  class="badge bg-success-transparent"> {{ __('active') }} </span>
                                    </td>
                                    <td> {{ formateDate(product.product.tracked_at) }} </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a style="width:120px" target="_blank" :href="product.product.source_link" class="btn btn-sm btn-warning"><i class="ri-eye-line"></i> {{ __('source Link') }} </a>
                                            <a style="width:120px" target="_blank" :href="'/product/'+product.product.id" class="btn btn-sm btn-info"><i class="ri-eye-line"></i> {{ __('store Link') }} </a>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="traking_products.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                </div>
            </div>
        </auth-layout>
    </div>
</template>


<script>

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
    traking_products:Object,
    sizes:Array,
    colors:Array,
  },
  data(){
    return {
        form:useForm({
            search:'',
        }),
        item:Object,
        checked:new Array,

    }
  },
  methods:{
    filter(){
        this.form.get('/admin/products_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.products.data ? this.checked.length == this.products.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.products.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>


<style>
td{padding: 5px !important;}
</style>
