<template>
    <div>
        <div class="modal fade" id="TrakingModal" tabindex="-1"
    aria-labelledby="TrakingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="TrakingModalLabel1">add traking code</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"> traking code </label>
                    <input type="text" class="form-control" v-model="vform.traking_code">
                    <div class="text-danger" v-html="vform.errors.get('traking_code')" />
                </div>
                <div class="mb-3">
                    <label class="form-label"> external shipping company </label>
                    <select v-model="vform.external_shipping_company_id" class="form-control">
                        <option :value="shipping_company.id" v-for="shipping_company,index in shipping_companies" :key="index">  {{ shipping_company.name }} </option>
                    </select>
                    <div class="text-danger" v-html="vform.errors.get('external_shipping_company_id')" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <button @click="update_traking_code" :disabled="vform.busy" type="button" class="btn btn-primary">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="btn-list mb-3">
                    <button @click="change_status(2)" :disabled="checked.length==0" class="btn btn-success"> Accept orders </button>
                    <button @click="change_status(1)" :disabled="checked.length==0" class="btn btn-danger"> Refuse orders </button>
                    <button data-bs-toggle="modal" data-bs-target="#TrakingModal" :disabled="checked.length==0" class="btn btn-info"> Add Traking code </button>
                    <button @click="excel_export()" :disabled="checked.length==0" class="btn btn-success"> Excel Export  </button>
                </div>
                <div class="card custom-card">
                    <div class="table-responsive" v-if="order_requests.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"><input v-model="checkAll" class="form-check-input" type="checkbox"
                                    id="checkboxNoLabel" aria-label="..."></th>


                                <th scope="col"> order Id </th>
                                <th scope="col"> company product id </th>
                                <th scope="col"> user </th>
                                <th scope="col"> product </th>
                                <th scope="col"> Traking Code </th>
                                <th scope="col"> Date </th>
                                <th scope="col">{{ __('vendor status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="company_request,index in order_requests.data" :key="index">
                                <th scope="row"><input v-model="checked" :value="company_request.id" class="form-check-input"
                                    type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
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

                                <td> {{ company_request.traking_code }} </td>

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
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a target="_blank" :href="company_request.order_item.product.source_link"
                                              class="btn btn-icon btn-sm btn-secondary"><i class="ri-eye-line"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="order_requests.links"/>
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

import AuthLayout from '../VendorLayouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import {Form} from 'vform';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
    props: {
        order_requests: Object,
        company_name: String,
        shipping_companies:Array
    },
    data() {
        return {
            form: useForm({
                search: '',
                status:null,
                checked:[]
            }),
            vform: new Form({
                id:null,
                status:null,
                traking_code:null,
                external_shipping_company_id:null,
                checked:[]
            }),
            item: Object,
            checked: new Array
        }
    },
    methods: {
        change_status(status){
            this.form.status=status
            this.form.checked=this.checked
            this.form.post(route('vendor.update_order_request_status'))
        },
        update_traking_code(){
            this.vform.checked=this.checked
            this.vform.post(route('vendor.update_traking_code'))
                .then((resp)=>{
                    let filtered = this.order_requests.data.filter((el)=>{
                        return this.checked.includes(el.id)
                    })
                    filtered.forEach((el)=>{

                            el.traking_code=this.vform.traking_code

                    })
                    this.vform.traking_code=null
                    this.checked=[]
                    $('#TrakingModal').modal('hide')
                })
        },
        excel_export(){
            this.vform.checked=this.checked
            this.vform.post(route('vendor.export_orders_session'))
                .then((resp)=>{
                    window.location.href='/vendor/export-orders'
                })
        }

    },
    computed: {
        checkAll: {

            get: function () {
                return this.order_requests.data ? this.checked.length == this.order_requests.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.order_requests.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
