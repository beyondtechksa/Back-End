<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/shipping_companies" :id="item.id" :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
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
                                <button v-if="checked.length>0 && check_permissions(['delete vendor'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add vendor'])" :href="route('shipping_companies.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="shipping_companies.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('name')}}</th>
                                    <th scope="col">{{__('email')}}</th>
                                    <th scope="col">{{__('phone')}}</th>
                                    <th scope="col">{{__('status')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="vendor,index in shipping_companies.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="vendor.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    <div class="">
                                        <img width="50" v-if="vendor.logo" v-lazy="vendor.logo" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                    <td>
                                        {{vendor.name}}

                                    </td>
                                    <td>
                                        {{vendor.email}}
                                    </td>

                                    <td>
                                        {{vendor.phone}}
                                    </td>


                                   <td>
                                    <div class="custom-toggle-switch d-flex align-items-center">
                                            <input :id="'testcheckbox'+vendor.id" type="checkbox" @change="update_status(vendor)"  :checked="vendor.status==1">
                                            <label :for="'testcheckbox'+vendor.id"  class="label-primary"></label>

                                        </div>
                                   </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['view vendor'])" :href="route('orders.vendor_requests',vendor.id)" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i> orders </Link>
                                            <Link v-if="check_permissions(['edit vendor'])" :href="route('shipping_companies.edit',vendor.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="shipping_companies.links" />
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
    shipping_companies:Object
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
        this.form.get(route('shipping_companies.index'))
    },
    update_status(shipping_company){
        axios.post('/admin/update_shipping_company_status',{shipping_company_id:shipping_company.id})
    }
  },
  computed: {
            checkAll: {
            get: function () {
                return this.shipping_companies.data ? this.checked.length == this.shipping_companies.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.shipping_companies.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
