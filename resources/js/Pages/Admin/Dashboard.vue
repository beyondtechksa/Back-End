<template>

    <auth-layout>
      <Head title="Dashboard" />
      <div class="container-fluid">
        <div class="row mt-4 mb-3">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                <Link :href="route('orders.index')">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                
                                <span class="rounded px-3 py-2 bg-primary-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Sales  </div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        <!-- {{ total_sell}}  --> 0 SAR
                                    </span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+4.2%</span> this month</span>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                </Link>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                <Link :href="route('products.index')">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0">
                                <span class="rounded px-3 py-2 bg-secondary-transparent">
                                    <i class="ri-product-hunt-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Products</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{total_products}}
                                    </span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </Link>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0">
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                  <i class="ri-user-3-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Visitors</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{total_users}}

                                    </span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="fs-12 mb-0">Decreased by <span class="badge bg-danger-transparent text-danger mx-1">-7.6%</span> this month</span>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0">
                                <span class="rounded px-3 py-2 bg-warning-transparent">
                                  <i class="ri-shopping-cart-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total Orders</div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ total_orders }}
                                    </span>
                                </div>
<!--                                <div>-->
<!--                                    <span class="fs-12 mb-0">Increased by <span class="badge bg-success-transparent text-success mx-1">+2.5%</span> this month</span>-->
<!--                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
        <div class="col-md-6">
        <div class="card custom-card">
        <div class="card-body bar-chart py-0">
                <Bar
                        id="my-chart-id"
                        :options="chartOptions"
                        :data="chartData"
                    />
                </div>
        </div>
        </div>
        <div class="col-md-6">
        <div class="card custom-card">
        <div class="card-body bar-chart py-0">
                <Bar
                        id="my-chart-id"
                        :options="chartOptions"
                        :data="chartData2"
                    />
                </div>
        </div>
        </div>
        </div>

        <div class="card custom-card" id="cart-container-delete">
            <div class="card-header">
                <div class="card-title">
                    Orders List
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('user') }}</th>
                                <th scope="col">{{ __('subtotal amount') }}</th>
                                <th scope="col">{{ __('shipping') }}</th>
                                <th scope="col">{{ __('discount') }}</th>
                                <th scope="col">{{ __('total amount') }}</th>
                                <th scope="col">{{ __('Date') }}</th>
                                <th scope="col">{{ __('status') }}</th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                             <tr v-for="order,index in orders" :key="index">
                                 <td>
                                     <span > {{ order.name }} </span>
                                 </td>
                                 <td>
                                     {{ order.subtotal_amount }}
                                 </td>
                                 <td>
                                     {{ order.shipping }}
                                 </td>
                                 <td>
                                     {{ order.discount }}
                                 </td>
                                 <td>
                                     {{ order.total_amount }}
                                 </td>

                                 <td>
                                     {{ formateDate(order.created_at) }}
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
                                     <div class="hstack gap-2 fs-15">
                                         <Link :href="'/admin/view-order/'+order.id"
                                               v-if="check_permissions(['view order'])"
                                               class="btn btn-icon btn-sm btn-info"><i class="ri-eye-line"></i></Link>
                                     </div>
                                 </td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    </auth-layout>
</template>

<script>
import AuthLayout from './Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'

import { Bar } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)


export default {
  props:['total_users','total_products','total_orders','total_sell','orders','customerChart','orderChart','year'],
  components: { AuthLayout, PageHeader, Bar },
  data(){
    return {
        chartData: {
            year:this.year,
            labels: [ 'Jan', 'Feb', 'Mär', 'Apr','May','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
            datasets: [
                {
                    label: 'orders',
                    //  data: this.data.months,
                     data: [this.orderChart['1'],this.orderChart['2'],this.orderChart['3'],this.orderChart['4'],this.orderChart['5'], this.orderChart['6']
                         ,this.orderChart['7'],this.orderChart['8'],this.orderChart['9'],this.orderChart['10'],this.orderChart['11'],this.orderChart['12']],
                     backgroundColor: '#845adf',
                }
            ]
        },
        chartData2: {
            year:this.year,
            labels: [ 'Jan', 'Feb', 'Mär', 'Apr','May','Jun','Jul','Aug','Sep','Okt','Nov','Dez'],
            datasets: [
                {
                    label: 'customers',
                    //  data: this.data.months,
                     data: [this.customerChart['1'],this.customerChart['2'],this.customerChart['3'],this.customerChart['4'],this.customerChart['5'], this.customerChart['6']
                         ,this.customerChart['7'],this.customerChart['8'],this.customerChart['9'],this.customerChart['10'],this.customerChart['11'],this.customerChart['12']],
                     backgroundColor: '#845adf',
                }
            ]
        },
        chartOptions: {
            // responsive: true
             height: 140

        }
    }
  }

}
</script>

