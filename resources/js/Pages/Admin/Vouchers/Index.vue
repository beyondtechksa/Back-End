<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/vouchers" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete voucher'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add voucher'])" :href="route('vouchers.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="vouchers.data.length>0">
                        <table class="table  table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('name')}}</th>
                                    <th scope="col">{{__('code')}}</th>
                                    <th scope="col">{{__('reference')}}</th>
                                    <th scope="col">{{__('ref details')}}</th>
                                    <th scope="col">{{__('currency')}}</th>
                                    <th scope="col">{{__('amount')}}</th>
                                    <th scope="col">{{__('valid untill')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="voucher,index in vouchers.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="voucher.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                        {{voucher.name}}
                                    </td>
                                    <td>
                                        {{voucher.code}}
                                    </td>
                                    <td>
                                        {{voucher.reference}}
                                    </td>
                                    <td>
                                        {{voucher.reference_details}}
                                    </td>



                                    <td>
                                     <span v-if="voucher.currency">   {{voucher.currency.prefix}} </span>
                                    </td>

                                    <td>
                                        {{voucher.amount}}
                                    </td>
                                    <td>
                                        {{voucher.valid_untill}}
                                    </td>



                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['edit voucher'])" :href="route('vouchers.edit',voucher.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!voucher.is_default && check_permissions(['delete voucher'])" @click="item=voucher" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="vouchers.links" />
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
    vouchers:Object
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
        this.form.get('/admin/vouchers_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.vouchers.data ? this.checked.length == this.vouchers.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.vouchers.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
