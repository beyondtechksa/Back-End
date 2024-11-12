

<template>

    <Head :title="$page.props.page_title" />
      <app>
        <div class="bread_cam">
      <div class="container-cum">
        <div class="navigation-url">
          <ul>
            <li>
              <Link :href="route('welcome')">{{__('Home')}}</Link>
            </li>
            <li>
              <img src="/home/img/arrow.svg" alt="" />
            </li>
            <li>
              <a href="#"> {{$page.props.page_title}} </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- ====== bread Cam  ===== -->

    <header class="CheckOutHeader-sm">
      <div class="pt-5">
        <div class="shop-header-container">
          <div class="prev-control">
            <Link :href="route('welcome')" class="prev-btn">
              <img src="/home/img/arrow.svg" alt="" />
            </Link>
          </div>
          <div class="catagory-title">
            <h3 class="text-center">{{$page.props.page_title}}</h3>
          </div>
        </div>
      </div>
    </header>

    <main class="my-cart">
      <div class="cart-container" v-if="carts.length">
        <div class="container-cum">
          <form @submit.prevent="go_checkout()" class="row">
          <div class="row">
            <div class="col-lg-7">
                <div class="My-cart-product">
                <div class="title-container">
                  <h2>{{__('Checkout')}}</h2>
                  <p>
                    {{__('A Checkout is a counter where you pay for things you are buying!')}}
                  </p>
                </div>

                <!-- ===== Check out === -->

                <div class="checkout-pro">
                  <div class="Checkout-iconbox">
                    <div class="MapLogo">
                      <img src="/home/img/Map.svg" alt="" />
                      <p>{{__('Delivery')}}</p>
                    </div>
                    <span class="bar-check"></span>
                    <div class="Payment-logo">
                      <img src="/home/img/payment-w.svg" alt="" />

                      <p>{{__('Payment')}}</p>
                    </div>
                  </div>
                  <div class="send-order">
                    <h3>{{__('Where can we send your order?')}}</h3>
                    <div class="select-order">
                    <div class="d-flex gap-2">
                      <div class="checkbox" v-for="address,index in addresses" :key="index">
                        <input :value="address.id" v-model="form.favourite_address" name="addressRadio" type="radio" :id="'address'+index">
                        <label class="px-2" :for="'address'+index"> {{ address.type }} </label>
                      </div>
                    </div>
                    <div class="text-danger mt-3" v-html="form.errors.favourite_address" />
                    <a href="javascript:void(0)" class="site-btn-sm" @click="go_to_redirect_url('cart/checkout/address','addresses-create')"> {{ __('Add New Address') }} </a>
                    </div>
                  </div>
                  <div class="checkout-form">
                    <div class="row">
                      <div class="input-box col-12">
                        <img src="/home/img/icon-user-lg.svg" alt="" />
                        <input :disabled="true" v-model="form.name" type="text" :placeholder="__('Name')" />
                      </div>
                      <div class="input-box col-6">
                        <img src="/home/img/email.svg" alt="" />
                        <input :disabled="true" v-model="form.email" type="text" :placeholder="__('Email Address')" />
                      </div>
                      <div class="input-box col-6">
                        <img src="/home/img/phone-Regular.svg" alt="" />
                        <input :disabled="true" v-model="form.phone" type="text" :placeholder="__('Phone Number')" />
                      </div>
                      <!-- <div class="input-box col-12">
                        <img src="/home/img/map-marker-Regular.svg" alt="" />
                        <input :disabled="true" v-model="form.address" type="text" :placeholder="__('Address')" />
                      </div> -->

                      </div>
                  </div>

                  <!-- ==== Responsive Check out form ===== -->

                  <div class="responsive-check-out">
                    <form action="" class="row">
                      <div class="input-box col-6">
                        <input type="email" placeholder="Email" />
                      </div>
                      <div class="input-box col-6">
                        <input type="text" placeholder="Full Names" />
                      </div>
                      <div class="input-box col-6">
                        <input
                          type="text"
                          placeholder="Address / Street Name"
                        />
                      </div>
                      <div class="input-box col-6">
                        <select name="" id="">
                          <option value="" selected>City</option>
                        </select>
                      </div>
                      <div class="input-box col-12">
                        <select name="" id="">
                          <option value="" selected>Area</option>
                        </select>
                      </div>
                      <div class="number-box col-12">
                        <div class="number-code">
                          <input type="text" placeholder="+966" />
                        </div>
                        <div class="number-input">
                          <input type="text" placeholder="Phone Number" />
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5">
                <div class="order-details">
              <order-details :carts="carts"></order-details>
              <div class="order-button mt-5">
              <button :disabled="form.processing">{{__('Proceed to Checkout')}}</button>
              </div>
              </div>
            </div>
          </div>
          </form>
        </div>
      </div>

      <!-- ======= Emty Card ======= -->
       <div class="emty-card" v-else>
        <div class="card-img">
          <div class="">
            <img class="" src="/home/img/emty.jpg" alt="" />
          </div>
        </div>
      </div>

      <!-- =====  Emty Card ====== -->


      <!-- ===== -->
    </main>
      </app>

  </template>



  <script>
    import App from '@/HomeLayouts/AppLayout.vue';
    import ProductBox from './Components/ProductBox.vue';
    import { useForm } from '@inertiajs/vue3';
    import OrderDetails from './Components/OrderDetails.vue';

  export default {
      components: { App, ProductBox, OrderDetails },
      props:{
        carts:Array,
        addresses:Array,
      },
      mounted(){

      },
      data(){
        return {
          form:useForm({
            name:this.$page.props.auth.user.name,
            email:this.$page.props.auth.user.email,
            phone:this.$page.props.auth.user.phone,
            favourite_address:this.check_favourite_address(),
            // address:this.check_selected_address(),
          })
        }
      },
      methods:{
        go_checkout(){
          this.form.post(route('cart.go_checkout'))
        },
        check_favourite_address(){
          if(this.addresses.length>0){
            let address=this.addresses.find((e)=>e.favourite==1)
            if(address){
              return address.id
            }
            return null
          }
          return null
        },
        // check_selected_address(){
        //   if(this.addresses.length>0){
        //     let address=this.addresses.find((e)=>e.id==this.check_favourite_address())
        //     if(address){
        //       return address.details
        //     }
        //     return null
        //   }
        //   return null
        // }
      },

  }

  </script>

<style>
.emty-card img{margin: 0 auto;}
</style>


