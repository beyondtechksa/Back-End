<template>
    <div>
        <change-status :order="order"></change-status>
        <div class="modal fade" id="productModal" tabindex="-1"
        aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="productModalLabel1">Manage Order</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="add_product">
                    <div class="mb-3">
                        <label> product </label>
                        <v-select  v-model="vform.product" :options="products" :reduce="product => product" label="sku" />
                        <div class="text-danger" v-html="vform.errors.get('product_id')"></div>
                    </div>
                    <div class="mb-3">
                        <label> quantity </label>
                        <input type="number" class="form-control" v-model="vform.quantity" >
                        <div class="text-danger" v-html="vform.errors.get('quantity')"></div>
                    </div>
                    <div v-if="available_sizes().length>0" class="mb-3">
                        <label> size </label>
                        <v-select v-model="vform.size" :options="available_sizes()" :reduce="size => size.translated_name" label="translated_name" />
                        <div class="text-danger" v-html="vform.errors.get('size')"></div>
                    </div>
                    <div v-if="available_colors().length>0" class="mb-3">
                        <label> color </label>
                        <v-select v-model="vform.color" :options="available_colors()" :reduce="color => color.translated_name" label="translated_name" />
                        <div class="text-danger" v-html="vform.errors.get('color')"></div>
                    </div>
                    <div class="mb-3">
                        <button :disabled="form.busy" type="submit" class="btn btn-primary"> Add </button>
                    </div>
                </form>
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
                                <div class="card-header"> Order Details (#{{ order.id }}) </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn  btn-icon btn-primary">
                                            <i class="ri-calendar-line"></i>
                                        </span>
                                        <span> {{ formateDate(order.created_at) }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn  btn-icon btn-primary">
                                            <i class="ri-bank-card-line"></i>
                                        </span>
                                        <span> telr </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn  btn-icon btn-primary">
                                            <i class="ri-truck-line"></i>
                                        </span>
                                        <span> {{ order.shipping}}  {{ __('SAR') }}</span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                            <div class="card custom-card">
                                <div class="card-header"> Client Details </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn btn-icon btn-primary">
                                            <i class="ri-user-line"></i>
                                        </span>
                                        <span> {{ order.user.name }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn btn-icon btn-primary">
                                            <i class="ri-mail-line"></i>
                                        </span>
                                        <span> {{ order.user.email }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="btn  btn-icon btn-primary">
                                            <i class="ri-phone-line"></i>
                                        </span>
                                        <span> {{ order.user.phone}}  </span>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-12">
                            <div class="card custom-card">
                                <div class="card-header"> Shipping Address </div>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-md-4">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                        Address Name
                                        </span>
                                        <span> {{ order.address?order.address.type:null }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                        First Name
                                        </span>
                                        <span> {{ order.address?order.address.first_name:null }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                        Last Name
                                        </span>
                                        <span> {{ order.address?order.address.last_name:null }} </span>
                                    </div>
                                    </div>
                                    <div class="col-md-8">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            postal code
                                        </span>
                                        <span> {{ order.address?order.address.postal_code:null }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            country
                                        </span>
                                        <span> {{ order.address?order.address.country:null }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            city
                                        </span>
                                        <span> {{ order.address?order.address.city:null }} </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <span class="fw-bold">
                                            details
                                        </span>
                                        <span> {{ order.address?order.address.details:null }} </span>
                                    </div>
                                    </div>
                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-3">
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
                                                            <span class="fs-11 text-success">{{formateDate(order.created_at)}}</span>
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
                                                            <p class="fw-semibold mb-0 fs-14">processing</p>
                                                            <span class="fs-11 text-success"> {{ order.address.type }} </span>
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
                                                            <span class="fs-11 text-success"> {{ order.address.type }} </span>
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
                                                            <span :class="{'bg-primary text-white':order.status>=5,'bg-primary-transparent text-primary':order.status<5}" class="avatar avatar-md avatar-rounded">
                                                                <i class="ri-check-line"></i>
                                                            </span>
                                                        </div>
                                                        <div class="flex-fill">
                                                            <p class="fw-semibold mb-0 fs-14">Delivered</p>
                                                            <span class="fs-11 text-success"> {{ order.address.type }} </span>
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
                    </div> -->
                </div>

                <button class="btn btn-primary mb-2" data-bs-target="#productModal" data-bs-toggle="modal"> Add product to order </button>
                <div class="card custom-card">

                <div class="table-responsive">
                    <table class="table text-nowrap table-striped">
                        <thead>
                            <tr>
                                <th>item</th>
                                <th>source link</th>
                                <th>vendor</th>
                                <th>vendor status</th>
                                <th>quantity</th>
                                <th>price</th>
                                <th>total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order_item,index in order.order_items" :key="index">
                                <td>
                                    <a target="_blank" :href="route('product.show',{'id':order_item.product_id,'slug':order_item.product.slug})">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-xxl bg-light">
                                                <img v-lazy="order_item.product.image" alt="">
                                            </span>
                                        </div>
                                        <div>
                                            <div class="mb-1 fs-14 fw-semibold">
                                                <a href="javascript:void(0);">{{order_item.product.name_en}}</a>
                                            </div>
                                            <div class="mb-1" v-if="order_item.size">
                                                <span class="me-1">Size:</span><span class="text-muted">{{order_item.size}}</span>
                                            </div>
                                            <div class="mb-1" v-if="order_item.color">
                                                <span class="me-1">Color:</span><span class="text-muted"> {{ order_item.color }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </td>
                                <td>
                                   <a target="_blank" class="btn btn-primary btn-icon" :href="order_item.product.source_link">
                                     <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                                <td>
                                    <span v-if="order_item.company_request">
                                       <span v-if="order_item.company_request.vendor"> {{ order_item.company_request.vendor.name }} </span>
                                    </span>
                                </td>
                                <td>
                                    <span v-if="order_item.company_request">
                                    <span class="bg-primary badge text-white p-2" v-if="order_item.company_request.vendor_status==0">
                                            Pending
                                        </span>
                                        <span class="bg-danger badge text-white p-2" v-else-if="order_item.company_request.vendor_status==1">
                                            Refused
                                        </span>
                                        <span class="bg-success badge text-white p-2" v-else-if="order_item.company_request.vendor_status==2">
                                            Accepted
                                    </span>
                                    </span>
                                </td>
                                <td>{{ order_item.quantity }}</td>
                                <td>{{ order_item.price }}</td>
                                <td>{{ order_item.quantity * order_item.price }}</td>
                            </tr>
                            <tr>
                                <td colspan="3"> subtotal </td>
                                <td> {{order.subtotal_amount}}  (SAR)</td>
                            </tr>
                            <tr>
                                <td colspan="3"> shipping (including vat) </td>
                                <td> {{order.shipping}}   (SAR)</td>
                            </tr>
                            <tr>
                                <td colspan="3"> discount </td>
                                <td> {{order.discount}}  (%)</td>
                            </tr>
                            <tr>
                                <td colspan="3"> vat </td>
                                <td> {{order.vat}}</td>
                            </tr>
                            <tr>
                                <td colspan="3"> Total </td>
                                <td> <strong>{{order.total_amount}}  (SAR) </strong> </td>
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

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import { Form } from 'vform';
import ChangeStatus from './ChangeStatus.vue';
import vSelect from 'vue-select'
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, ChangeStatus, vSelect},
  props:{
    order:Object,
    products:Array,
    colors:Array,
    sizes:Array,
  },
  data(){
    return {
        form:useForm({
            search:'',
        }),
        vform:new Form({
            id:null,
            order_id:this.order.id,
            status:null,
            product:{},
            quantity:1,
            size:null,
            color:null,
        }),
        item:Object,
    }
  },
    methods:{

        available_colors(){
            if(this.vform.product){
                if(this.vform.product.sizes_ids){
                    return this.colors.filter((el)=>{
                        if(typeof this.vform.product.colors_ids==='array'){
                            return this.vform.product.colors_ids.includes(el.id)
                        }
                        return false
                    })
                }
                return []
            }
            return []
        },
        available_sizes(){
            if(this.vform.product){
                if(this.vform.product.sizes_ids){
                    return this.sizes.filter((el)=>{
                        if(typeof this.vform.product.sizes_ids==='array'){
                            return this.vform.product.sizes_ids.includes(el.id)
                        }
                        return false
                    })
                }
                return []
            }
            return []
        },
        add_product(){
            this.vform.product?this.vform.product_id=this.vform.product.id:this.vform.product_id=null
            this.vform.post('/admin/create_order_item')
                 .then((resp)=>{
                    this.order.order_items.push(resp.data)
                    this.order.subtotal_amount+=parseFloat(resp.data.price)
                    this.order.total_amount+=parseFloat(resp.data.price)
                    $('#productModal').modal('hide')
                 })
        }
    },
  computed: {

        }

}

</script>
<style>
@import "vue-select/dist/vue-select.css";
</style>
