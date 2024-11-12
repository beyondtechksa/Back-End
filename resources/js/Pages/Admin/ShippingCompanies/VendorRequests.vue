<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <p class="fs-25 fw-bold text-center"> {{ vendor.name }} </p>
                <div class="btn-list mb-3">
                    <Link :href="route('orders.vendor_requests',vendor.id)" class="btn btn-primary"> All orders </Link>
                    <button @click="filter(0)"  class="btn btn-primary"> New orders </button>
                    <button @click="filter(2)"  class="btn btn-success"> Accepted orders </button>
                    <button @click="filter(1)"  class="btn btn-danger"> Refused orders </button>
                </div>

                <div class="card custom-card">

                    <div class="table-responsive" v-if="vendor_requests.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"> order Id </th>
                                <th scope="col"> company product id </th>
                                <th scope="col"> user </th>
                                <th scope="col"> product </th>
                                <th scope="col"> Date </th>
                                <th scope="col">{{ __('vendor status') }}</th>
                                <th scope="col"> Traking code </th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="company_request,index in vendor_requests.data" :key="index">

                                <td>#{{company_request.order_id}} </td>
                                <td>#{{company_request.company_product_id}} </td>

                                <td>
                                    <span v-if="company_request.user"> {{ company_request.user.name }} </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center" v-if="company_request.order_item">
                                        <div class="me-3" v-if="company_request.order_item.product">
                                            <span class="avatar avatar-xxl bg-light">
                                                <img v-lazy="company_request.order_item.product.image" alt="">
                                                    </span>
                                                </div>
                                                <div>
                                            <div class="mb-1 fs-14 fw-semibold">
                                                <a href="javascript:void(0);">{{company_request.order_item.product.name_en}}</a>
                                            </div>
                                            <div class="mb-1">
                                                <span class="me-1">Size:</span><span class="text-muted">{{company_request.order_item.size}}</span>
                                            </div>
                                            <div class="mb-1">
                                                <span class="me-1">Color:</span><span class="text-muted">{{company_request.order_item.color}}</span>
                                            </div>
                                            <div class="mb-1">
                                              <span class="me-1">Quantity:</span><span class="text-muted">{{company_request.order_item.quantity}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>



                                <td>
                                    {{ formateDate(company_request.created_at) }}
                                </td>
                                <td>
                                        <span class="bg-primary badge text-white p-2" v-if="company_request.vendor_status==0">
                                            Pending
                                        </span>
                                        <span class="bg-danger badge text-white p-2" v-else-if="company_request.vendor_status==1">
                                            Refused
                                        </span>
                                        <span class="bg-success badge text-white p-2" v-else-if="company_request.vendor_status==2">
                                            Accepted
                                        </span>

                                </td>
                                <td> {{ company_request.traking_code }} </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a target="_blank" :href="company_request.order_item.product.source_link"
                                              class="btn btn-sm btn-secondary"><i class="ri-eye-line"></i> source link
                                        </a>
                                        <a target="_blank" :href="'/product/'+company_request.order_item.product_id"
                                              class="btn btn-sm btn-secondary"><i class="ri-eye-line"></i> store link
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="vendor_requests.links"/>
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

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
    props: {
        vendor_requests: Object,
        vendor: Object,
    },
    data() {
        return {
            form: useForm({
                search: '',
                status:null,
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
        filter(status=null){
            this.form.status=status
            this.form.get(route('orders.vendor_requests',this.vendor.id))
        },

    },
    computed: {
        checkAll: {

            get: function () {
                return this.vendor_requests.data ? this.checked.length == this.vendor_requests.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.vendor_requests.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
