<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <p class="fs-25 fw-bold text-center"> {{ company_name }} </p>
                <div class="card custom-card">

                    <div class="table-responsive" v-if="company_requests.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"> order Id </th>
                                <th scope="col"> company product id </th>
                                <th scope="col"> user </th>
                                <th scope="col"> product </th>
                                <th scope="col"> Date </th>
                                <th scope="col">{{ __('status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="company_request,index in company_requests.data" :key="index">

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
                                        <span class="bg-primary badge text-white p-2" v-if="company_request.status==0">
                                            Pending
                                        </span>
                                        <span class="bg-danger badge text-white p-2" v-else-if="company_request.status==1">
                                            Sent
                                        </span>

                                </td>
                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <Link :href="'/admin/company_request_bill/'+company_request.id"
                                              v-if="check_permissions(['view company_request'])"
                                              class="btn btn-icon btn-sm btn-secondary"><i class="ri-bill-line"></i>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="company_requests.links"/>
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
        company_requests: Object,
        company_name: String,
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
            this.form.get('/admin/company_requests_filter')
        },

        select_company_request(order){
            this.vform.id=order.id
            this.item=order
        },
        change_status(status) {
            this.vform.status = status
            this.vform.post('/admin/update_order')
                .then((resp) => {
                    $('#editModal').modal('hide')
                    let order = this.company_requests.data.find((e) => e.id == this.vform.id)
                    order.status = resp.data
                })
        },
        async exportTable() {
            try {
                const response = await axios.get('/admin/company_requests-export/excel', {
                    responseType: 'blob'
                });
                const blob = new Blob([response.data], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'company_requests.xlsx';
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
                return this.company_requests.data ? this.checked.length == this.company_requests.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.company_requests.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
