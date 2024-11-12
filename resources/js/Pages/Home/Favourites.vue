

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
      <div class="cart-container cart-page" v-if="favourites.length">
        <div class="container-cum">
            <div class="title-container">
            <h2>
            {{__('Favourites')}}
            <span>({{favourites.length}} {{__('Item')}})</span>
            </h2>

        </div>

          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6 col-6" v-for="favourite,index in favourites" :key="index">
            <product-box :product="favourite.product"></product-box>
            </div>
          </div>
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
      <!-- ====== Responsive Card   ====== -->
      <div class="responsive-cart">
        <div class="">
          <div class="card-count">
            <h3>1 Item</h3>
          </div>
          <div class="card-content">
            <div class="d-flex align-items-center">
              <div class="img-card">
                <img src="/home/img/product-5.png" alt="" />
              </div>
              <div class="card-content-dis">
                <h4>Puff Sleeves Sweetheart Neck Midi Dress</h4>
                <h5>Quantity: <span>1</span></h5>
                <h5>Size: <span>L</span></h5>
                <h5>Color : <span>Blue Beach</span></h5>
                <h5>Brand : <span> ZARA</span></h5>
              </div>
            </div>
            <div class="">
              <div class="order-date d-flex align-items-center gap-1">
                <h4 class="d-flex align-items-center gap-2">
                  <img src="/home/img/package-black.svg" alt="" />
                  Delivery by :
                </h4>
                <h4 class="date">26 Jan - 28 Jan</h4>
                <h2 class="price">SAR 40</h2>
              </div>
            </div>
          </div>

          <div class="coupon-container">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center gap-2">
                <img src="/home/img/coupon.png" alt="" />
                <p>Enter a Coupon or Promo code</p>
              </div>
              <div class="onselected">
                <a
                  href="#"
                  data-bs-toggle="offcanvas"
                  data-bs-target="#offcanvasBottom"
                  aria-controls="offcanvasBottom"
                  >Select</a
                >
              </div>
            </div>
          </div>

          <div class="order-details-res">
            <div class="title">
              <h3>Order Details</h3>
            </div>
            <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>Sub-total</p>
              <p>SAR 40</p>
            </div>
            <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>Coupon savings</p>
              <a href="#" class="coupon">Add Coupon</a>
            </div>
            <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>Shipping</p>
              <p>FREE</p>
            </div>
            <div class="total-price-check">
              <div class="d-flex justify-content-between align-items-center">
                <h4>Total Amount:</h4>
                <div class="d-flex align-items-center gap-3">
                  <h3>SAR 40</h3>
                </div>
              </div>
            </div>
          </div>
          <div class="total-price-check border-0 pt-4 pb-4 cart-checkout">
            <div class="d-flex justify-content-between align-items-center">
              <h4>Total Amount:</h4>
              <div class="d-flex align-items-center gap-3">
                <h3>SAR 40</h3>
                <a href="checkout.html">Checkout</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ====== Responsive Card   ====== -->
      <div
        class="offcanvas offcanvas-bottom cuponCodeCanvas"
        tabindex="-1"
        id="offcanvasBottom"
        aria-labelledby="offcanvasBottomLabel"
      >
        <div class="offcanvas-header">
          <header class="CheckOutHeader-sm">
            <div class="pt-5">
              <div class="shop-header-container">
                <div class="prev-control">
                  <a
                    href="#"
                    class="prev-btn btn-close text-reset"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close"
                  >
                    <img src="/home/img/arrow.svg" alt="" />
                  </a>
                </div>
                <div class="catagory-title">
                  <h3 class="text-center">Discount code</h3>
                </div>
              </div>
            </div>
          </header>
        </div>
        <div class="offcanvas-body small">
          <div class="">
            <form action="">
              <div class="input-box">
                <input type="text" placeholder="Enter a coupon or promo code" />
              </div>

              <div class="button-fild">
                <button type="submit">Add code</button>
              </div>
            </form>
          </div>
        </div>
      </div>

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
        favourites:Array,
      },
      mounted(){

      },
      data(){
        return {
          form:useForm({

          })
        }
      },
      methods:{
        filter(){
          this.form.get('shop',{ preserveState: false, preserveScroll: true })
        },
        load_more(){
          this.form.limit+=24
          this.filter()
        }
      },

  }

  </script>

<style>
.emty-card img{margin: 0 auto;}
</style>


