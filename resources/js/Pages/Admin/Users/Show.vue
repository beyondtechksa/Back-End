<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                

                <div class="row">
                    <div class="col-lg-3">
                        <div class="card custom-card">
                            <div class="card-header">
                                <div class="card-title">
                                    Personal Info
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-2 fw-semibold">
                                                Name :
                                            </div>
                                            <span class="fs-12 text-muted">{{user.name}}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-2 fw-semibold">
                                                Email :
                                            </div>
                                            <span class="fs-12 text-muted">{{user.email}}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-2 fw-semibold">
                                                Phone :
                                            </div>
                                            <span class="fs-12 text-muted">{{user.phone}}</span>
                                        </div>
                                    </li>
                                 
                                   
                                    <li class="list-group-item">
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="me-2 fw-semibold">
                                                Gender :
                                            </div>
                                            <span class="fs-12 text-muted">{{user.gender}}</span>
                                        </div>
                                    </li>
                                </ul>

                                <div class="btn-list text-center mt-3 mb-0">
                                        <a :href="'mailto:'+user.email" class="btn btn-icon btn-primary-light btn-wave waves-effect waves-light">
                                            <i class="ri-mail-line fw-semibold"></i>
                                        </a>
                                        <a :href="'tel:'+user.phone" class="btn btn-icon btn-danger-light btn-wave waves-effect waves-light">
                                            <i class="ri-phone-line fw-semibold"></i>
                                        </a>
                                    
                                        
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="card custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                                      
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#orders-tab" type="button" role="tab" aria-controls="orders-tab" aria-selected="true"><i class="ri-gift-line me-1 align-middle d-inline-block"></i>Orders</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="posts-tab" data-bs-toggle="tab" data-bs-target="#addresses-tab" type="button" role="tab" aria-controls="posts-tab-pane" aria-selected="false" tabindex="-1"><i class="ri-bill-line me-1 align-middle d-inline-block"></i>Addresses</button>
                                    </li>
                                  
                                </ul>
                            </div>   
                                 
                            
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade p-0 border-0 active show" id="orders-tab" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                <div class="table-responsive" v-if="orders.data.length>0">
                                    <table class="table text-nowrap table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{__('Sub total')}}</th>
                                                <th scope="col">{{__('Shipping')}}</th>
                                                <th scope="col">{{__('Discount')}}</th>
                                                <th scope="col">{{__('Total')}}</th>
                                                <th scope="col">{{__('Date')}}</th>
                                                <th scope="col">{{__('Status')}}</th>
                                                <th scope="col">{{__('Actions')}}</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="order,index in orders.data" :key="index">

                                            <td>
                                                {{order.subtotal_amount}}
                                            </td>
                                                <td>
                                                    {{order.shipping}}

                                                </td>
                                                <td>
                                                    {{order.discount}}

                                                </td>
                                                <td>
                                                    {{order.total_amount}}

                                                </td>

                                                <td>
                                                    {{ order.order_created_at }}

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
                                                    <Link :href="route('orders.show',order.id)" class="btn btn-icon btn-primary btn-sm"><i class="ri-eye-line"></i></Link>

                                                </td>

                                            </tr>
                                        </tbody>

                                    </table>
                                    <div class="pagination py-3 mx-2 px-2">
                                        <Pagination :links="orders.links"/>
                                    </div>
                                </div>
                                <div v-else>
                                    <no-elements></no-elements>
                                </div>
                            </div>
                            <div class="tab-pane fade p-0 border-0" id="addresses-tab" role="tabpanel" aria-labelledby="activity-tab" tabindex="0">
                                <div class="table-responsive" v-if="user.addresses.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                            <tr>
                                <th scope="col">{{__('name')}}</th>
                                <th scope="col">{{__('type')}}</th>
                                <th scope="col">{{__('details')}}</th>
                                <th scope="col">{{__('street')}}</th>
                                <th scope="col">{{__('building name')}}</th>
                                <th scope="col">{{__('city')}}</th>
                                <th scope="col">{{__('country')}}</th>
                                <th scope="col">{{__('favourite')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="address,index in user.addresses" :key="index">

                                <td>
                                    {{address.first_name+' '+address.last_name}}
                                </td>
                                
                                <td>
                                    {{address.type}}

                                </td>
                                <td>
                                    {{address.details}}

                                </td>
                                <td>
                                    {{address.street}}

                                </td>
                                <td>
                                    {{address.building_name}}

                                </td>
                                <td>
                                    {{address.city}}

                                </td>
                                <td>
                                    {{address.country}}

                                </td>
                                <td>
                                    <span v-if="address.favourite"> {{__('Yes')}} </span>
                                    <span v-else> {{__('No')}} </span>
                                </td>

                            </tr>
                            </tbody>

                        </table>

                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                            </div>
                        </div>
                               
                    </div>
                </div>
                    </div>
                

                </div>






            </div>
        </auth-layout>
    </div>
</template>



<script>

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {Head, useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';

export default {
  components: {Head, AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
      user:Object,
    orders:Object
  },
  data(){

  },


}

</script>
