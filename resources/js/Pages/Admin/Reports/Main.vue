<template>

    <auth-layout>
        <Head title="Dashboard"/>
        <div class="container-fluid">
            <div class="d-flex justify-content-between mt-3">
                <div class="date" style="width:300px">
                    <flat-pickr placeholder="select" :config="config" class="form-control" v-model="form.range"/>
                </div>
                <button class="btn btn-primary" @click="filterRange"> search</button>
            </div>
            <div class="row mt-4 mb-3">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                <span class="rounded px-3 py-2 bg-primary-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2"> Sales</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{sales}}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                  <i class="ri-user-3-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2"> Visitors</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ users_percentage }} %
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-warning-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">New Orders</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{new_orders}}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-warning-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Completed Orders</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{done_orders}}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                  <i class=" ri-coins-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Revenues</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{revenues}}
                                    </span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="rounded px-3 py-2 bg-secondary-transparent">
                                    <i class="ri-product-hunt-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Total Products</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{products}}
                                    </span>
                                    </div>
                                    <!-- <div>
                                        <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                    <i class="ri-product-hunt-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Active Products</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{active_products}}
                                    </span>
                                    </div>
                                    <!-- <div>
                                        <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div
                                    class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="rounded px-3 py-2 bg-danger-transparent">
                                    <i class="ri-product-hunt-fill fs-25"></i>
                                </span>
                                </div>
                                <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                    <div class="mb-2">Users Count</div>
                                    <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{users_count}}
                                    </span>
                                    </div>
                                    <!-- <div>
                                        <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'

import {Bar} from 'vue-chartjs'
import {Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import {Form} from 'vform'

export default {
    props: [
        'sales', 'new_orders', 'done_orders', 'revenues', 'products', 'active_products','users_percentage','users_count'
    ],
    components: {AuthLayout, PageHeader, Bar, flatPickr},
    data() {
        return {
            chartData: {
                year: 2023,
                labels: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
                datasets: [
                    {
                        label: 'orders',
                        //  data: this.data.months,
                        data: [100, 300, 400, 600, 100, 700, 400, 900, 400, 200, 100, 500],
                        backgroundColor: '#845adf',
                    }
                ]
            },
            chartData2: {
                year: 2023,
                labels: ['Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez'],
                datasets: [
                    {
                        label: 'customers',
                        //  data: this.data.months,
                        data: [100, 300, 400, 600, 100, 700, 400, 900, 400, 200, 100, 500],
                        backgroundColor: '#845adf',
                    }
                ]
            },
            chartOptions: {
                // responsive: true
                height: 140

            },
            config: {
                wrap: true, // set wrap to true only when using 'input-group'
                altFormat: 'M j, Y',
                altInput: true,
                mode: 'range',
                dateFormat: 'Y-m-d',
                // locale: Hindi, // locale for this instance only
            },
            form: new Form({
                range: null
            })
        }
    },
    methods: {
        filterRange() {
            this.$inertia.replace(this.route('filter.main', {range: this.form.range}))
        }
    }

}
</script>

