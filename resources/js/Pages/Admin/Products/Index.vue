<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/products" :id="item.id" :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header py-1">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" type="text" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button v-if="checked.length>0 && check_permissions(['delete product'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <Link class="btn btn-warning" :href="route('products.search')"><i class="ri-filter-fill me-1 align-bottom"></i> {{ __('advanced filter') }} </Link>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add product'])" :href="route('products.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="products.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('added by')}}</th>
                                    <th scope="col">{{__('price')}}</th>
                                    <th scope="col">{{__('quantity')}}</th>
                                    <th scope="col">{{__('status')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in products.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="product.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                        <div class="hover-image">
                                        <img width="60" v-if="product.image" v-lazy="product.image" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="60" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>
                                    <td>{{ substr(product.name_en+' / '+product.name_ar,40)}}</td>
                                    <td>
                                        <span v-if="product.admin_id"> {{ product.admin.name }} </span>
                                    </td>
                                    <td>{{product.price}}</td>
                                    <td>{{product.quantity}}</td>
                                    <td>
                                        <span v-if="product.status==0" class="badge bg-warning-transparent"> {{ __('not active') }} </span>
                                        <span v-else  class="badge bg-success-transparent"> {{ __('active') }} </span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a style="width:120px" target="_blank" :href="product.group_link" class="btn btn-sm btn-secondary"><i class="ri-eye-line"></i> {{ __('group Link') }} </a>
                                            <a style="width:120px" target="_blank" :href="product.source_link" class="btn btn-sm btn-warning"><i class="ri-eye-line"></i> {{ __('source Link') }} </a>
                                            <a style="width:120px" target="_blank" :href="'/product/'+product.id+'/'+product.slug" class="btn btn-sm btn-info"><i class="ri-eye-line"></i> {{ __('store Link') }} </a>
                                            <a v-if="check_permissions(['edit product'])" target="_blank" :href="route('products.edit',product.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></a>
                                            <a v-if="check_permissions(['delete product'])" @click="item=product" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="products.links" />
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
    products:Object,

  },
  data(){
    return {
        form:useForm({
            search:'',
        }),
        item:Object,
        checked:new Array
    }
  },
  methods:{
    filter(){
        this.form.get(route('products.index'))
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
