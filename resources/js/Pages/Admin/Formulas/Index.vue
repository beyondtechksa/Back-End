<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/formulas" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete pricing formula'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add pricing formula'])" :href="route('formulas.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="formulas.data.length>0">
                        <table class="table  table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('name')}}</th>
                                    <th scope="col">{{__('VAT')}}</th>
                                    <th scope="col">{{__('Packaging&Shipping Fees')}}</th>
                                    <th scope="col">{{__('Management Fees')}}</th>
                                    <th scope="col">{{__('Profit Percentage')}}</th>
                                    <th scope="col">{{__('Shipping Percentage')}}</th>
                                    <th scope="col">{{__('Manual Price Adjustment')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="formula,index in formulas.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="formula.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                        {{formula.name}}
                                    </td>
                                    <td>
                                        {{formula.discount_percentage_selling_price}}
                                    </td>
                                    <td>
                                        {{formula.packaging_shipping_fees}}
                                    </td>
                                    <td>
                                        {{formula.management_fees}}
                                    </td>
                                    <td>
                                        {{formula.profit_percentage}}
                                    </td>

                                    <td>
                                        {{formula.commercial_percentage}}
                                    </td>
                                    <td>
                                        {{formula.manual_price_adjustment}}
                                    </td>



                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['edit pricing formula'])" :href="route('formulas.edit',formula.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!formula.is_default && check_permissions(['delete pricing formula'])" @click="item=formula" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="formulas.links" />
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
    formulas:Object
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
        this.form.get('/admin/formulas_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.formulas.data ? this.checked.length == this.formulas.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.formulas.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
