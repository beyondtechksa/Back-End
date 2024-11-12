<template>

    <auth-layout>
      <Head title="Dashboard" />
      <div class="container-fluid">
        <div class="row mt-4 mb-3">
            <div class="col-lg-6 col-sm-6 col-md-6 col-xxl-3">
                <Link :href="route('vendor.order_requests')">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                
                                <span class="rounded px-3 py-2 bg-info-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Total orders  </div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ total_orders}}
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
                <Link :href="route('vendor.order_requests',0)">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                
                                <span class="rounded px-3 py-2 bg-primary-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Pending orders  </div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ pending_orders}}
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
                <Link :href="route('vendor.order_requests',1)">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                
                                <span class="rounded px-3 py-2 bg-danger-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Refused orders  </div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ refused_orders}}
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
                <Link :href="route('vendor.order_requests',2)">
                <div class="card custom-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0">
                                
                                <span class="rounded px-3 py-2 bg-success-transparent">
                                    <i class="ri-shopping-bag-fill fs-25"></i>
                                </span>
                            </div>
                            <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-8 col-sm-8 col-8 ps-0">
                                <div class="mb-2">Accepted orders  </div>
                                <div class="text-muted mb-1 fs-12">
                                    <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom">
                                        {{ accepted_orders}}
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
          

        </div>


     

    </div>
    </auth-layout>
</template>

<script>
import AuthLayout from '../VendorLayouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'




export default {
  props:['total_orders','pending_orders','accepted_orders','refused_orders'],
  components: { AuthLayout, PageHeader, },
  data(){
    return {
   
    }
  }

}
</script>

