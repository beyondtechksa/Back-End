

<template>
   
  <Head title="Dashboard" />
  <app>
    <main class="user-admin">
      <div class="admin-container">
        <div class="container-cum">
          <div class="row mt-4">
            <side-bar></side-bar>
            <page-content>
              <div class="user-account-details">
        <div class="account-title">
            <h3>{{__('Track Order')}}</h3>
            <p>{{__('Enter your order Id getting from your order page')}}</p>
        </div>
        <div class="General-info">

            <div class="General-form">
            <form @submit.prevent="update()" class="row justify-content-center">
                <div class="col-lg-6">
                <div class="user-forminput">
                    <label for="First Name">{{__('Order Id')}}</label>
                    <input v-model="form.order_id" type="text" />
                    <div class="text-danger"  v-html="form.errors.order_id" />
                </div>
 
                <div class="submit-button">
                    <button class="submit" type="submit">
                    {{__('Track Order')}}
                    </button>
                
                </div>
                </div>
            </form>
            </div>
        </div>
        <div class="General-info Order-details-info" v-if="order">
                      <!-- ==== Order Details  ===== -->
                      <div class="Order-details">
                        <h3>
                          {{__('Order')}} <span class="order-number">#{{ order.id }}</span>
                          <span class="sub-title"> On it’s way</span>
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

                      <div class="track-order-my mt-4">
                        <div class="progress-bar">
                          <div class="d-flex justify-content-center">
                            <div class="">
                              <div class="rounded-circel">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="60"
                                  height="60"
                                  viewBox="0 0 60 60"
                                  fill="none"
                                >
                                  <path
                                    d="M41.325 23.6725C42.0575 24.405 42.0575 25.5926 41.325 26.3251L28.825 38.8251C28.46 39.1901 27.98 39.375 27.5 39.375C27.02 39.375 26.54 39.1926 26.175 38.8251L18.675 31.3251C17.9425 30.5926 17.9425 29.405 18.675 28.6725C19.4075 27.94 20.595 27.94 21.3275 28.6725L27.5025 34.8474L38.6775 23.6725C39.4075 22.9425 40.5925 22.9425 41.325 23.6725ZM54.375 30C54.375 43.44 43.44 54.375 30 54.375C16.56 54.375 5.625 43.44 5.625 30C5.625 16.56 16.56 5.625 30 5.625C43.44 5.625 54.375 16.56 54.375 30ZM50.625 30C50.625 18.6275 41.3725 9.375 30 9.375C18.6275 9.375 9.375 18.6275 9.375 30C9.375 41.3725 18.6275 50.625 30 50.625C41.3725 50.625 50.625 41.3725 50.625 30Z"
                                    fill="white"
                                  />
                                </svg>
                              </div>
                              <h3>{{ __('Placed') }}</h3>
                            </div>

                            <div class="bar-bg" :class="{'uncompleted':order.status<3}"></div>
                            <div>
                              <div class="rounded-circel" :class="{'uncompleted-order':order.status<3}">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="60"
                                  height="60"
                                  viewBox="0 0 60 60"
                                  fill="none"
                                >
                                  <path
                                    d="M41.325 23.6725C42.0575 24.405 42.0575 25.5926 41.325 26.3251L28.825 38.8251C28.46 39.1901 27.98 39.375 27.5 39.375C27.02 39.375 26.54 39.1926 26.175 38.8251L18.675 31.3251C17.9425 30.5926 17.9425 29.405 18.675 28.6725C19.4075 27.94 20.595 27.94 21.3275 28.6725L27.5025 34.8474L38.6775 23.6725C39.4075 22.9425 40.5925 22.9425 41.325 23.6725ZM54.375 30C54.375 43.44 43.44 54.375 30 54.375C16.56 54.375 5.625 43.44 5.625 30C5.625 16.56 16.56 5.625 30 5.625C43.44 5.625 54.375 16.56 54.375 30ZM50.625 30C50.625 18.6275 41.3725 9.375 30 9.375C18.6275 9.375 9.375 18.6275 9.375 30C9.375 41.3725 18.6275 50.625 30 50.625C41.3725 50.625 50.625 41.3725 50.625 30Z"
                                    fill="white"
                                  />
                                </svg>
                              </div>
                              <h3>{{ __('processing') }}</h3>
                            </div>
                            <div class="bar-bg" :class="{'uncompleted':order.status<5}"></div>
                            <div>
                              <div class="rounded-circel" :class="{'uncompleted-order':order.status<5}">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="60"
                                  height="60"
                                  viewBox="0 0 60 60"
                                  fill="none"
                                >
                                  <path
                                    d="M41.325 23.6725C42.0575 24.405 42.0575 25.5926 41.325 26.3251L28.825 38.8251C28.46 39.1901 27.98 39.375 27.5 39.375C27.02 39.375 26.54 39.1926 26.175 38.8251L18.675 31.3251C17.9425 30.5926 17.9425 29.405 18.675 28.6725C19.4075 27.94 20.595 27.94 21.3275 28.6725L27.5025 34.8474L38.6775 23.6725C39.4075 22.9425 40.5925 22.9425 41.325 23.6725ZM54.375 30C54.375 43.44 43.44 54.375 30 54.375C16.56 54.375 5.625 43.44 5.625 30C5.625 16.56 16.56 5.625 30 5.625C43.44 5.625 54.375 16.56 54.375 30ZM50.625 30C50.625 18.6275 41.3725 9.375 30 9.375C18.6275 9.375 9.375 18.6275 9.375 30C9.375 41.3725 18.6275 50.625 30 50.625C41.3725 50.625 50.625 41.3725 50.625 30Z"
                                    fill="white"
                                  />
                                </svg>
                              </div>
                              <h3>{{ __('Shipped') }}</h3>
                            </div>

                            <div class="bar-bg" :class="{'uncompleted':order.status<6}"></div>
                            <div class="">
                              <div class="rounded-circel" :class="{'uncompleted-order':order.status<6}">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  width="60"
                                  height="60"
                                  viewBox="0 0 60 60"
                                  fill="none"
                                >
                                  <path
                                    d="M41.325 23.6725C42.0575 24.405 42.0575 25.5926 41.325 26.3251L28.825 38.8251C28.46 39.1901 27.98 39.375 27.5 39.375C27.02 39.375 26.54 39.1926 26.175 38.8251L18.675 31.3251C17.9425 30.5926 17.9425 29.405 18.675 28.6725C19.4075 27.94 20.595 27.94 21.3275 28.6725L27.5025 34.8474L38.6775 23.6725C39.4075 22.9425 40.5925 22.9425 41.325 23.6725ZM54.375 30C54.375 43.44 43.44 54.375 30 54.375C16.56 54.375 5.625 43.44 5.625 30C5.625 16.56 16.56 5.625 30 5.625C43.44 5.625 54.375 16.56 54.375 30ZM50.625 30C50.625 18.6275 41.3725 9.375 30 9.375C18.6275 9.375 9.375 18.6275 9.375 30C9.375 41.3725 18.6275 50.625 30 50.625C41.3725 50.625 50.625 41.3725 50.625 30Z"
                                    fill="white"
                                  />
                                </svg>
                              </div>
                              <h3>{{ __('Delivered') }}</h3>
                            </div>
                          </div>
                        </div>
                        <div
                          class="Order-ship d-flex align-items-center justify-content-between"
                        >
                          <div>
                            <h4>Your Order have been shipped already</h4>
                            <p v-if="order.external_shipping_company">{{order.external_shipping_company.name}} (tracking number : 29372983628)</p>
                          </div>
                          <div class="" v-if="order.external_shipping_company">
                            <a target="_blank" :href="order.external_shipping_company.link" class="Notify"
                              >Track</a
                            >
                          </div>
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
import { useForm } from '@inertiajs/vue3';

export default{
  components: { App, SideBar, PageContent },
  props:{
    order_id:String,
    order:Object,
},
  data(){
    return {
      form : useForm({
      order_id:this.order_id,
    })
    }
  },
  methods:{
    update(){
      this.form.post(route('profile.track_order_post'))
    }
  }
}
</script>
    