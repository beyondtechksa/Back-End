<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/coupons" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete coupon'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add coupon'])" :href="route('coupons.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="coupons.data.length>0">
                        <table class="table  table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('name')}}</th>
                                    <th scope="col">{{__('code')}}</th>
                                    <th scope="col">{{__('reference')}}</th>
                                    <th scope="col">{{__('ref details')}}</th>
                                    <th scope="col">{{__('discount type')}}</th>
                                    <th scope="col">{{__('type')}}</th>
                                    <th scope="col">{{__('discount amount')}}</th>
                                    <th scope="col">{{__('valid untill')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="coupon,index in coupons.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="coupon.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                        {{coupon.name}}
                                    </td>
                                    <td>
                                        {{coupon.code}}
                                    </td>
                                    <td>
                                        {{coupon.reference}}
                                    </td>
                                    <td>
                                        {{coupon.reference_details}}
                                    </td>

                                    <td>
                                        {{coupon.discount_type}}
                                    </td>

                                    <td>
                                        {{coupon.type}}
                                    </td>

                                    <td>
                                        {{coupon.discount_amount}}
                                    </td>
                                    <td>
                                        {{coupon.valid_untill}}
                                    </td>



                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['edit coupon'])" :href="route('coupons.edit',coupon.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!coupon.is_default && check_permissions(['delete coupon'])" @click="item=coupon" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="coupons.links" />
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
    coupons:Object
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
        this.form.get('/admin/coupons_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.coupons.data ? this.checked.length == this.coupons.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.coupons.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
