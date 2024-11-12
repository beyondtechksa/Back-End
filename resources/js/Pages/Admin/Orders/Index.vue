<template>
    <div>

        <change-status :order="item"></change-status>

        <div class="modal fade" id="editModal" tabindex="-1"
             aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="editModalLabel1">Manage Order</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button @click="change_status(1)" class="btn btn-success w-100 mb-2"> Mark as Delivred</button>
                        <button @click="change_status(2)" class="btn btn-danger w-100"> Mark as Refused</button>
                    </div>

                </div>
            </div>
        </div>

        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/orders" :id="item.id"
                              :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <!-- <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" type="text" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                </div>
                            </div>
                        </div> -->
                        <div class="d-flex w-100 justify-content-end">
                            <div class="p-2">
                                <button class="btn btn-primary" @click="exportTable">
                                    <i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('export') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="orders.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"><input v-model="checkAll" class="form-check-input" type="checkbox"
                                                       id="checkboxNoLabel" aria-label="..."></th>
                                <th scope="col"> order Id </th>
                                <th scope="col">{{ __('user') }}</th>
                                <th scope="col">{{ __('subtotal amount') }}</th>
                                <th scope="col">{{ __('shipping') }}</th>
                                <th scope="col">{{ __('discount') }}</th>
                                <th scope="col">{{ __('vat') }}</th>
                                <th scope="col">{{ __('total amount') }}</th>
                                <th scope="col"> Company Shipping Coast </th>
                                <th scope="col"> Weight </th>
                                <th scope="col"> tracking code </th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order,index in orders.data" :key="index">
                                <th scope="row"><input v-model="checked" :value="order.id" class="form-check-input"
                                                       type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                <td>#{{order.id}} </td>

                                <td>
                                    <span v-if="order.user_id"> {{ order.user.name }} </span>
                                </td>
                                <td>
                                    {{ order.subtotal_amount }}
                                </td>
                                <td>
                                    {{ order.shipping }}
                                </td>
                                <td>
                                    {{ order.discount }}
                                </td>
                                <td>
                                    {{ order.vat }}
                                </td>
                                <td>
                                    {{ order.total_amount }}
                                </td>
                                <td>
                                   <span v-if="order.company_shipping "> {{ order.company_shipping }} SAR </span>
                                </td>
                                <td>
                                    {{ order.weight }}
                                </td>
                                <td>
                                    {{ order.traking_code }}
                                </td>

                                <td>
                                    {{ formateDate(order.created_at) }}
                                </td>
                                <td>
                                        <span class="bg-primary badge text-white p-2" v-if="order.status==0">
                                            Pending
                                        </span>
                                    <span class="bg-danger badge text-white p-2" v-else-if="order.status==1">
                                            Refused
                                        </span>
                                    <span class="bg-secondary badge text-white p-2" v-else-if="order.status==2">
                                            Accepted
                                        </span>
                                    <span class="bg-warning badge text-white p-2" v-else-if="order.status==3">
                                            processing
                                        </span>
                                    <span class="bg-info badge text-white p-2" v-else-if="order.status==4">
                                            shipping
                                        </span>
                                    <span class="bg-success badge text-white p-2" v-else-if="order.status==5">
                                            delivered
                                        </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <Link :href="'/admin/order_bill/'+order.id"
                                              v-if="check_permissions(['view order'])"
                                              class="btn btn-icon btn-sm btn-secondary"><i class="ri-bill-line"></i>
                                        </Link>
                                        <Link :href="'/admin/view-order/'+order.id"
                                              v-if="check_permissions(['view order'])"
                                              class="btn btn-icon btn-sm btn-info"><i class="ri-eye-line"></i>
                                        </Link>
                                        <!-- <button @click="select_order(order)" data-bs-toggle="modal"
                                                data-bs-target="#editModal" v-if="check_permissions(['edit order'])"
                                                class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i>
                                        </button> -->
                                        <a v-if="check_permissions(['delete order'])" @click="item=order"
                                           data-bs-target="#warningModal" data-bs-toggle="modal"
                                           href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i
                                            class="ri-delete-bin-line"></i></a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="orders.links"/>
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
import {useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import {Form} from 'vform';
import ChangeStatus from './ChangeStatus.vue';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, ChangeStatus},
    props: {
        orders: Object
    },
    data() {
        return {
            form: useForm({
                search: '',
            }),
            vform: new Form({
                id: null,
                status: null
            }),
            item: Object,
            checked: new Array
        }
    },
    methods: {
        filter(){
            this.form.get('/admin/orders_filter')
        },

        select_order(order){
            this.vform.id=order.id
            this.item=order
        },
        change_status(status) {
            this.vform.status = status
            this.vform.post('/admin/update_order')
                .then((resp) => {
                    $('#editModal').modal('hide')
                    let order = this.orders.data.find((e) => e.id == this.vform.id)
                    order.status = resp.data
                })
        },
        async exportTable() {
            try {
                const response = await axios.get('/admin/orders-export/excel', {
                    responseType: 'blob'
                });
                const blob = new Blob([response.data], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'orders.xlsx';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error('Error downloading the file:', error);
            }
        }
    },
    computed: {
        checkAll: {

            get: function () {
                return this.orders.data ? this.checked.length == this.orders.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.orders.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
