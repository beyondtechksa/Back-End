

<template>

    <Head title="Dashboard" />
    <app>
      <main class="user-admin">
        <div class="admin-container">
          <div class="container-cum">
            <div class="row mt-4">
              <side-bar></side-bar>
              <page-content>
                <div class="user-account-details Order-account">
                    <div class="account-title">
                      <h3>{{ __('My Orders') }}</h3>
                    </div>
                    <div v-if="orders.length>0">
                    <div class="General-info Order-details-info" v-for="order,index in orders" :key="index">
                      <!-- ==== Order Details  ===== -->
                      <div class="Order-details">
                        <h3>
                          {{ __('Order') }} <span class="order-number">#{{order.id}}</span>
                          <span class="sub-title">On it’s way</span>
                        </h3>
                        <h4>
                          {{ __('Subtotal') }} :
                          <span class="order-date">{{order.subtotal_amount}}</span>
                        </h4>
                        <h4>
                          {{ __('Shipping Fee') }} :
                          <span class="order-date">{{order.shipping}}</span>
                        </h4>
                        <h4>
                          {{ __('Discount') }} :
                          <span class="order-date">{{order.discount}}</span>
                        </h4>
                        <h4>
                          {{ __('Total Amount') }} :
                          <span class="order-date">{{order.total_amount}}</span>
                        </h4>
                      </div>
                      <div class="product-card" v-for="order_item,index in order.order_items" :key="index">
                        <div class="d-flex align-items-center gap-3">
                          <div class="img-card">
                            <img v-lazy="order_item.product.image" alt="" />
                          </div>
                          <div class="product-card-information">
                            <div class="product-title">
                              <Link :href="route('product.show',order_item.product.id)"><h2> {{ order_item.product['name_'+$page.props.locale] }} </h2></Link>
                            </div>
                            <div class="card-details">
                              <h3>{{__('Brand')}} : <span>{{order_item.product.brand.translated_name}}</span></h3>
                              <h3>
                                {{ __('Color') }} : <span> {{ order_item.translated_color }} </span> / {{ __('Quantity') }} :
                                <span>{{order_item.quantity}}/</span> {{ __('Size') }} : <span>{{ order_item.translated_size }}</span>
                              </h3>
                              <h2 class="price-tag">{{ __('SAR') }} {{ order_item.price }}</h2>


                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="d-flex gap-1">
                      <Link v-if="order.status==6" :href="route('profile.return_order',order.id)" class="track-order">{{ __('return item') }}</Link>
                      <Link :href="route('profile.track_order',order.id)" class="track-order">{{ __('Track Order') }}</Link>
                      </div>
                    </div>
                    </div>
                    <div class="emty-card" v-else>
                        <div class="card-img text-center">
                          <div class="">
                            <img class="" src="/home/img/emty.jpg" alt="" />
                          </div>
                          <div class="text-center mt-3">
                          <Link style="    margin: 0 auto;" :href="route('products.shop')" class="site-btn"> {{ __('shop now') }} </Link>
                          </div>
                        </div>
                      </div>
                  </div>
              </page-content>

            </div>
          </div>
        </div>

        <!-- ==== Responsive Admin  ==== -->


      </main>
    </app>

  </template>



  <script>
  import App from '@/HomeLayouts/AuthenticatedLayout.vue';
  import SideBar from './Components/SideBar.vue';
  import PageContent from './Components/PageContent.vue';
  import { Form } from 'vform';
  import { useForm } from '@inertiajs/vue3';

  export default{
    components: { App, SideBar, PageContent },
    props:{orders:Array},
    data(){
      return {
        form:useForm({
          type:null,
          details:null,
      })
      }
    },
    methods:{

    }
  }
  </script>

      <style>
      .emty-card img{margin: 0 auto;height: 300px;
    width: auto;}
  .sm-input{width:100px  ;  }
      </style>
