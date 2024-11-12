<template>
    <div>


        <div class="modal fade" id="editModal" tabindex="-1"
             aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="editModalLabel1">Manage Request</h6>
                        <button id="closeEditModal" type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button v-for="return_status,index in return_statuses" :key="index" @click="change_status(return_status.id)" class="btn btn-success w-100 mb-2"> {{ return_status.translated_name }} </button>
                    </div>

                </div>
            </div>
        </div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">

                        <div class="text-center gap-2">
                            <button @click="filter()" class="btn btn-primary" :class="{'btn-success':!return_status_id}"> {{ __('All') }} </button>
                            <button @click="filter(return_status.id)" v-for="return_status,index in return_statuses" :key="index" class="btn btn-primary mx-2" :class="{'btn-success':return_status_id==return_status.id}"> {{ return_status.translated_name }} </button>
                        </div>

                    </div>
                    <div class="table-responsive" v-if="return_items.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>

                                <th scope="col"> order Id </th>
                                <th scope="col"> {{__('Product')}}</th>
                                <th scope="col">{{ __('user') }}</th>
                                <th scope="col">{{ __('quantity') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="return_item,index in return_items.data" :key="index">
                                <td>#{{return_item.order_item.order_id}} </td>
                                <td>
                                    <a target="_blank" :href="route('product.show',{'id':return_item.order_item.product_id,'slug':return_item.order_item.product.slug})">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <span class="avatar avatar-xxl bg-light">
                                                <img v-lazy="return_item.order_item.product.image" alt="">
                                            </span>
                                        </div>
                                        <div>
                                            <div class="mb-1 fs-14 fw-semibold">
                                                <a href="javascript:void(0);">{{return_item.order_item.product.name_en}}</a>
                                            </div>
                                            <div class="mb-1" v-if="return_item.order_item.size">
                                                <span class="me-1">Size:</span><span class="text-muted">{{return_item.order_item.size}}</span>
                                            </div>
                                            <div class="mb-1" v-if="return_item.order_item.color">
                                                <span class="me-1">Color:</span><span class="text-muted"> {{ return_item.order_item.color }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </td>
                                <td>
                                    <span v-if="return_item.return_request.user"> {{ return_item.return_request.user.name }} </span>
                                </td>
                                <td>
                                    {{ return_item.quantity }}
                                </td>

                                <td>
                                    {{ formateDate(return_item.created_at) }}
                                </td>
                                <td>
                                    <span v-if="return_item.return_status">
                                        {{ return_item.return_status.translated_name }}
                                    </span>
                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <button @click="form.id=return_item.id" data-bs-toggle="modal" data-bs-target="#editModal"
                                              class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="return_items.links"/>
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
        return_items: Object,
        return_statuses:Array,
        return_status_id:String
    },
    data() {
        return {
            form: useForm({
                id:null,
                status_id: '',
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
        filter(status_id){
            this.form.status_id=status_id
            this.form.get(route('orders.return_requests'))
        },
        change_status(status_id) {
            this.form.status_id = status_id
            this.form.post(route('return_request.update_status'),{
                onSuccess :()=>{
                    document.getElementById('closeEditModal').click()
                }
            })
        },

    },
    computed: {

    }

}

</script>
