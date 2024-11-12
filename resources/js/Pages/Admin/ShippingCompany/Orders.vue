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
                <div class="d-flex justify-content-between align-items-center my-3">
                <div class="btn-list mb-3">
                    <!-- <button @click="excel_export()" :disabled="checked.length==0" class="btn btn-success"> Excel Export  </button> -->
                </div>
                <div>
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                    <select v-model="form.vendor_status" class="form-control"> 
                        <option :value="null">All Records </option>
                        <option :value="2">All  Accepted </option>
                        <!-- <option :value="1">Some Not Accepted </option> -->
                    </select>
                    <button @click="filter" class="btn btn-primary"> Filter </button>
                    </div>
                </div>
                </div>
                <div class="card custom-card">
                    <div class="table-responsive" v-if="orders.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"><input v-model="checkAll" class="form-check-input" type="checkbox"
                                    id="checkboxNoLabel" aria-label="..."></th>
                                

                                <th scope="col"> order Id </th>
                                <th scope="col"> user </th>
                                <th scope="col"> Date </th>
                                <th scope="col"> Total </th>
                                <th scope="col"> Status </th>
                                <th scope="col"> vendor Status </th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="order,index in orders.data" :key="index">
                                <th scope="row"><input v-model="checked" :value="order.id" class="form-check-input"
                                    type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                <td>#{{order.id}} </td>
                                
                                <td>
                                    <span v-if="order.user"> {{ order.user.name }} </span>
                                </td>

                                <td>
                                    {{ formateDate(order.created_at) }}
                                </td>
                                <td> {{ order.total_amount }} {{ order.currency }} </td>
                                <td>
                                        
                                    <span class="bg-primary badge text-white p-2" v-if="order.status==2">
                                            New
                                        </span>
                                    <span class="bg-warning badge text-white p-2" v-else-if="order.status==3">
                                            in house
                                        </span>
                                    <span class="bg-warning badge text-white p-2" v-else-if="order.status==4">
                                            processing
                                        </span>
                                    <span class="bg-info badge text-white p-2" v-else-if="order.status==5">
                                            shipping
                                        </span>
                                    <span class="bg-success badge text-white p-2" v-else-if="order.status==6">
                                            delivered
                                        </span>
                                </td>
                                <td>
                                    <span class="position-relative">
                                    <span :class="{'bg-success':order.vendor_status==2,'bg-danger':order.vendor_status==0}" class="position-absolute top-0 start-100 translate-middle p-2  border border-light rounded-circle">
                                                <span class="visually-hidden">New alerts</span>
                                            </span>
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <Link  :href="route('shipping.order.show',order.id)"
                                              class="btn btn-icon btn-sm btn-secondary"><i class="ri-eye-line"></i>
                                        </Link>
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

import AuthLayout from '../ShippingLayouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import {Form} from 'vform';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
    props: {
        orders: Object,
        company_name: String,
    },
    data() {
        return {
            form: useForm({
                search: '',
                status:null,
                vendor_status:null,
                checked:[]
            }),
            vform: new Form({
                id:null,
                status:null,
                traking_code:null,
                checked:[]
            }),
            item: Object,
            checked: new Array
        }
    },
    methods: {
        filter(){
            this.form.get(this.$page.props.ziggy.location)
        },
        change_status(status){
            this.form.status=status
            this.form.checked=this.checked
            this.form.post(route('vendor.update_order_request_status'))
        },
        update_traking_code(){
            this.vform.checked=this.checked
            this.vform.post(route('vendor.update_traking_code'))
                .then((resp)=>{
                    let filtered = this.orders.data.filter((el)=>{
                        return this.checked.includes(el.id)
                    })
                    filtered.forEach((el)=>{
                        if(el.vendor_status==0){
                            el.traking_code=this.vform.traking_code
                        }
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
