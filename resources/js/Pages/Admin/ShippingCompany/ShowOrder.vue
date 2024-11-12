<template>
    <div>
        <change-status :order="order"></change-status>
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
        <div class="modal fade" id="DetailsModal" tabindex="-1"
    aria-labelledby="DetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="DetailsModalLabel1">Add Shipment Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"> Shipping Price (SAR)</label>
                    <input type="number" class="form-control" v-model="vform.company_shipping">
                    <div class="text-danger" v-html="vform.errors.get('company_shipping')" />
                </div>
                <div class="mb-3">
                    <label class="form-label"> Weight </label>
                    <input type="text" class="form-control" v-model="vform.weight">
                    <div class="text-danger" v-html="vform.errors.get('weight')" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>
                <button @click="update_shipment" :disabled="vform.busy" type="button" class="btn btn-primary">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="card custom-card">
                                <div class="card-header"> Order Details </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Order ID:
                                        </span>
                                        <span> #{{order.id}} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Order Date:
                                        </span>
                                        <span> {{ formateDate(order.created_at) }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Payment Method
                                        </span>
                                        <span> telr: </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Traking code:
                                        </span>
                                        <span> {{ order.traking_code}}  </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Shipping Coast:
                                        </span>
                                        <span> {{ order.company_shipping}} SAR </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Shipping Weight:
                                        </span>
                                        <span> {{ order.weight}}  </span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="card custom-card">
                                <div class="card-header"> Client Details </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            ID:
                                        </span>
                                        <span> #{{ order.user_id }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Name:
                                        </span>
                                        <span> {{ order.user.name }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Address:
                                        </span>
                                        <span class="pe-2"> {{ order.address?order.address.country:null}}  </span>
                                        <span class="pe-2"> {{ order.address?order.address.city:null}}  </span>
                                        <span class="pe-2"> {{ order.address?order.address.street:null}}  </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Tax ID:
                                        </span>
                                        <span > {{ order.address?order.address.tax_id:null}}  </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Shipping Address:
                                        </span>
                                        <span> {{ order.address?order.address.details:null}}  </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Email:
                                        </span>
                                        <span> {{ order.user.email }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Phone Number:
                                        </span>
                                        <span> {{ order.user.phone}}  </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            Pstal Code:
                                        </span>
                                        <span> {{ order.address?order.address.postal_code:null}}  </span>
                                    </div>
                                </div>
                            </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    Order Tracking
                                </div>
                                <div class="ms-auto text-success">#{{ order.id }}</div>
                            </div>
                            <div class="card-body">
                                <div class="order-track">
                                    <div class="accordion" id="basicAccordion">
                                        <div class="accordion-item border-0 bg-transparent">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="px-0 pt-0 collapsed" href="javascript:void(0)" role="button" data-bs-toggle="collapse" data-bs-target="#basicOne" aria-expanded="false" aria-controls="basicOne">
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span class="avatar avatar-md bg-primary avatar-rounded">
                                                                <i class="ri-shopping-cart-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">Order Placed</p>
                                                            <span class="fs-11 text-success">by user : {{formateDate(order.created_at)}}</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="accordion" id="basicAccordion">
                                        <div class="accordion-item border-0 bg-transparent">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="px-0 pt-0 collapsed" href="javascript:void(0)" role="button" data-bs-toggle="collapse" data-bs-target="#basicOne" aria-expanded="false" aria-controls="basicOne">
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span :class="{'bg-primary text-white':order.status>=2,'bg-primary-transparent text-primary':order.status<2}" class="avatar avatar-md avatar-rounded"  >
                                                                <i class="ri-check-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">Order Accepted</p>
                                                            <span class="fs-11 text-success"> by admin </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="accordion" id="basicAccordion">
                                        <div class="accordion-item border-0 bg-transparent">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="px-0 pt-0 collapsed" href="javascript:void(0)" role="button" data-bs-toggle="collapse" data-bs-target="#basicOne" aria-expanded="false" aria-controls="basicOne">
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span :class="{'bg-primary text-white':order.status>=3,'bg-primary-transparent text-primary':order.status<3}" class="avatar avatar-md avatar-rounded">
                                                                <i class="ri-boxing-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">in house</p>
                                                            <span class="fs-11 text-success"> by shipping </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="accordion" id="basicAccordion">
                                        <div class="accordion-item border-0 bg-transparent">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="px-0 pt-0 collapsed" href="javascript:void(0)" role="button" data-bs-toggle="collapse" data-bs-target="#basicOne" aria-expanded="false" aria-controls="basicOne">
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span :class="{'bg-primary text-white':order.status>=4,'bg-primary-transparent text-primary':order.status<4}" class="avatar avatar-md avatar-rounded">
                                                                <i class="ri-truck-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">Shipping</p>
                                                            <span class="fs-11 text-success"> by shipping </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="accordion" id="basicAccordion">
                                        <div class="accordion-item border-0 bg-transparent">
                                            <div class="accordion-header" id="headingOne">
                                                <a class="px-0 pt-0 collapsed" href="javascript:void(0)" role="button" data-bs-toggle="collapse" data-bs-target="#basicOne" aria-expanded="false" aria-controls="basicOne">
                                                    <div class="d-flex mb-0">
                                                        <div class="me-2">
                                                            <span :class="{'bg-primary text-white':order.status>=6,'bg-primary-transparent text-primary':order.status<6}" class="avatar avatar-md avatar-rounded">
                                                                <i class="ri-check-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">Delivered</p>
                                                            <span class="fs-11 text-success"> by shipping </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                                <div class="mt-3">
                                    <button data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-primary w-100"><i class="ri-edit-line"></i> change status</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card custom-card">
                    <div class="btn-list my-3 px-3">
                        <button @click="excel_export()" :disabled="checked.length==0" class="btn btn-success"> Excel Export  </button>
                        <button data-bs-toggle="modal" data-bs-target="#TrakingModal" class="btn btn-primary"> Add traking code  </button>
                        <button data-bs-toggle="modal" data-bs-target="#DetailsModal" class="btn btn-primary"> Update Shipment Details  </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>


                                <th scope="col"><input v-model="checkAll" class="form-check-input" type="checkbox"
                                    id="checkboxNoLabel" aria-label="..."></th>
                                <th scope="col"> order Id </th>
                                <th scope="col"> company product id </th>
                                <th scope="col"> product </th>
                                <th scope="col">unit price </th>
                                <th scope="col"> Traking Code </th>
                                <th scope="col"> Date </th>
                                <th scope="col">{{ __('vendor status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="company_request,index in company_requests" :key="index">
                                <th scope="row"><input v-model="checked" :value="company_request.id" class="form-check-input"
                                    type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                <td>#{{company_request.order_item.order_id}} </td>
                                <td>#{{company_request.company_product_id}} </td>
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

                                <td> {{ company_request.order_item.price }} </td>
                                <td> {{ company_request.traking_code }}
                                    <a @click="copy(company_request.traking_code)" href="javascript:void(0)" class="btn btn-primary btn-sm me-2"> <i class="ri-file-line"></i> </a>
                                    <a class="btn btn-primary btn-sm" v-if="company_request.external_shipping_company" target="_blank" :href="company_request.external_shipping_company.link"> <i class="ri-truck-line"></i> </a>
                                </td>

                                <td>
                                    {{ formateDate(company_request.order_item.created_at) }}
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

                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

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
import ChangeStatus from './ChangeStatus.vue';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, ChangeStatus},
    props: {
        order: Object,
        company_requests: Object,
        shipping_companies: Object,
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
                company_shipping:null,
                weight:null,
                checked:[]
            }),
            item: Object,
            checked:[]
        }
    },
    methods: {
        change_status(status){
            this.form.status=status
            this.form.checked=this.checked
            this.form.post(route('vendor.update_order_request_status'))
        },
        update_traking_code(){
            this.vform.id=this.order.id
            this.vform.post(route('shipping.update_traking_code'))
                .then((resp)=>{
                    $('#TrakingModal').modal('hide')
                    this.order.traking_code=this.vform.traking_code
                    this.vform.traking_code=null
                })
        },
        update_shipment(){
            this.vform.id=this.order.id
            this.vform.post(route('shipping.update_shipment'))
                .then((resp)=>{
                    $('#DetailsModal').modal('hide')
                    this.order.company_shipping=this.vform.company_shipping
                    this.order.weight=this.vform.weight
                    this.vform.company_shipping=null
                    this.vform.weight=null
                })
        },
        excel_export(){
            this.vform.checked=this.checked
            this.vform.post(route('vendor.export_orders_session'))
                .then((resp)=>{
                    window.location.href='/vendor/export-orders'
                })
        },
        copy(text) {
            // Get the text from the input field

            // Use the Clipboard API to copy the text
            navigator.clipboard.writeText(text).then(function() {
                alert("Text copied to clipboard: " + text);
            }).catch(function(error) {
                console.error("Failed to copy text: ", error);
            });
        }

    },
    computed: {
        checkAll: {

            get: function () {
                return this.company_requests ? this.checked.length == this.company_requests.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.company_requests.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
