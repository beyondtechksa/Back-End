<template>
    <div>

        <auth-layout>
            <div class="container-fluid">
                <!-- <delete-modal @clearChecked="checked=[]" url="/admin/bills" :id="item.id"
                              :checked="checked"></delete-modal> -->


                <page-header></page-header>

                    <div class="row">
                    <div  class="col-lg-9">
                        <div class="card custom-card">
                        <div class="card-header">
                        Bills
                        </div>
                        <div class="table-responsive" v-if="bills.data.length>0">
                        <table class="table text-nowrap table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Client</th>
                                                <th scope="col">Invoice ID</th>
                                                <th scope="col">Order ID</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">Status</th>

                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="invoice-list" v-for="bill,index in bills.data" :key="index">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2 lh-1">
                                                            <span v-if="bill.user" class="avatar avatar-sm avatar-rounded">
                                                                <img v-if="bill.user.avatar" v-lazy="bill.user.avatar" alt="">
                                                                <img v-else v-lazy="$page.props.auth.default_img" alt="">
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="mb-0 fw-semibold">{{bill.user?bill.user.name:bill.user_name }}</p>
                                                            <p class="mb-0 fs-11 text-muted">{{bill.user?bill.user.email:bill.user_email }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="fw-semibold text-primary">
                                                        #{{bill.bill_id}}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0);" class="fw-semibold text-primary">
                                                        #{{bill.order_id}}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{bill.date}}
                                                </td>
                                                <td>
                                                    {{bill.order.currency}} {{bill.order.total_amount}}
                                                </td>
                                                <td>
                                                    <span v-if="bill.paid_status" class="badge bg-success-transparent">Paid</span>
                                                    <span v-else class="badge bg-danger-transparent">Not Paid</span>
                                                </td>

                                                <td>
                                                    <Link class="btn btn-success-light btn-icon btn-sm" :href="route('bills.show',bill.id)"><i class="ri-printer-line"></i></Link>
                                                    <!-- <button @click="item=bill" class="btn btn-primary-light btn-icon ms-1 btn-sm invoice-btn" data-bs-toggle="modal" data-bs-target="#invoicModal"><i class="ri-printer-line"></i></button>
                                                    <button @click="item=bill,downloadPDF()" class="btn btn-primary-light btn-icon ms-1 btn-sm invoice-btn" data-bs-toggle="modal" data-bs-target="#invoicModal"><i class="ri-printer-line"></i></button> -->
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="bills.links"/>
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                        </div>

                    </div>
                    <div class="col-lg-3">
                        <div class="card custom-card">
                            <div class="card-body p-0">
                                <div class="p-4 border-bottom border-block-end-dashed d-flex align-items-top">
                                    <div class="svg-icon-background bg-primary-transparent me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" class="svg-primary"><path d="M13,16H7a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2ZM9,10h2a1,1,0,0,0,0-2H9a1,1,0,0,0,0,2Zm12,2H18V3a1,1,0,0,0-.5-.87,1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0l-3,1.72-3-1.72a1,1,0,0,0-1,0A1,1,0,0,0,2,3V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V13A1,1,0,0,0,21,12ZM5,20a1,1,0,0,1-1-1V4.73L6,5.87a1.08,1.08,0,0,0,1,0l3-1.72,3,1.72a1.08,1.08,0,0,0,1,0l2-1.14V19a3,3,0,0,0,.18,1Zm15-1a1,1,0,0,1-2,0V14h2Zm-7-7H7a1,1,0,0,0,0,2h6a1,1,0,0,0,0-2Z"></path></svg>
                                    </div>
                                    <div class="flex-fill">
                                        <h6 class="mb-2 fs-12">Total Invoices Amount
                                            <span class="badge bg-primary fw-semibold float-end">
                                                {{reports.all_bills.count}}
                                            </span>
                                        </h6>
                                        <div class="pb-0 mt-0">
                                            <div>
                                                <h4 class="fs-18 fw-semibold mb-2">$<span class="count-up" data-count="192">{{reports.all_bills.amount}}</span></h4>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 border-bottom border-block-end-dashed d-flex align-items-top">
                                    <div class="svg-icon-background bg-success-transparent me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="svg-success"><path d="M11.5,20h-6a1,1,0,0,1-1-1V5a1,1,0,0,1,1-1h5V7a3,3,0,0,0,3,3h3v5a1,1,0,0,0,2,0V9s0,0,0-.06a1.31,1.31,0,0,0-.06-.27l0-.09a1.07,1.07,0,0,0-.19-.28h0l-6-6h0a1.07,1.07,0,0,0-.28-.19.29.29,0,0,0-.1,0A1.1,1.1,0,0,0,11.56,2H5.5a3,3,0,0,0-3,3V19a3,3,0,0,0,3,3h6a1,1,0,0,0,0-2Zm1-14.59L15.09,8H13.5a1,1,0,0,1-1-1ZM7.5,14h6a1,1,0,0,0,0-2h-6a1,1,0,0,0,0,2Zm4,2h-4a1,1,0,0,0,0,2h4a1,1,0,0,0,0-2Zm-4-6h1a1,1,0,0,0,0-2h-1a1,1,0,0,0,0,2Zm13.71,6.29a1,1,0,0,0-1.42,0l-3.29,3.3-1.29-1.3a1,1,0,0,0-1.42,1.42l2,2a1,1,0,0,0,1.42,0l4-4A1,1,0,0,0,21.21,16.29Z"></path></svg>
                                    </div>
                                    <div class="flex-fill">
                                        <h6 class="mb-2 fs-12">Total Paid Invoices
                                            <span class="badge bg-success fw-semibold float-end">
                                                {{reports.paid_bills.count}}
                                            </span>
                                        </h6>
                                        <div>
                                            <h4 class="fs-18 fw-semibold mb-2">$<span class="count-up" data-count="68.83">{{reports.paid_bills.amount}}</span></h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-top p-4 border-bottom border-block-end-dashed">
                                    <div class="svg-icon-background bg-danger-transparent me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" class="svg-danger"><path d="M19,12h-7V5c0-0.6-0.4-1-1-1c-5,0-9,4-9,9s4,9,9,9s9-4,9-9C20,12.4,19.6,12,19,12z M12,19.9c-3.8,0.6-7.4-2.1-7.9-5.9C3.5,10.2,6.2,6.6,10,6.1V13c0,0.6,0.4,1,1,1h6.9C17.5,17.1,15.1,19.5,12,19.9z M15,2c-0.6,0-1,0.4-1,1v6c0,0.6,0.4,1,1,1h6c0.6,0,1-0.4,1-1C22,5.1,18.9,2,15,2z M16,8V4.1C18,4.5,19.5,6,19.9,8H16z"></path></svg>
                                    </div>
                                    <div class="flex-fill">
                                        <h6 class="mb-2 fs-12">Not Paid Invoices
                                            <span class="badge bg-danger fw-semibold float-end">
                                                {{reports.not_paid_bills.count}}
                                            </span>
                                        </h6>
                                        <div>
                                            <h4 class="fs-18 fw-semibold mb-2">$<span class="count-up" data-count="81.57">{{reports.not_paid_bills.amount}}</span></h4>

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
import {useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import {Form} from 'vform';
import ChangeStatus from './ChangeStatus.vue';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, ChangeStatus},
    props: {
        bills: Object,
        reports:Object
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
            item: null,
            checked: new Array
        }
    },
    methods: {
        downloadPDF() {
            var element = document.getElementById('pdf');
            html2pdf(element, {
                // margin: 20, // Set a default margin for all pages
                filename: 'bill.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98 // Higher quality for images
                },
                html2canvas: {
                    scale: 4, // Higher scale for better resolution
                    useCORS: true, // Allow cross-origin images
                    dpi: 300, // High DPI for better text clarity
                    letterRendering: true // Improves text rendering quality
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4',
                    orientation: 'portrait',
                    precision: 16 // Higher precision for better quality
                }

            });
        }
    },
    computed: {
        checkAll: {

            get: function () {
                return this.bills.data ? this.checked.length == this.bills.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.bills.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
