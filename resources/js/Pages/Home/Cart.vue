

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
      <div class="container-cum cart-page" v-if="carts.length">
        <div class="container-cum">
          <div class="row">
            <div class="col-lg-7">
              <div class="My-cart-product">
                <div class="title-container">
                  <h2>
                    {{__('My Cart')}}
                    <span>({{carts.length}} {{__('Item')}})</span>
                  </h2>
                  <p>
                    {{ __("Ready to Roll: Your Handpicked Selection Awaits in the Shopping Cart!") }}
                  </p>
                </div>
                <div class="selection d-flex gap-3 mt-3">
                      <label id="checkAll" class="custom-checkbox">
                        <input  v-model="checkAll" type="checkbox">
                        <span class="checkmark"></span>
                        {{ __('select all') }}
                      </label>
                    </div>
                <div class="product-card" v-for="cart,index in carts" :key="index">
                  <div class="d-flex align-items-center gap-3">
                    <div class="selection mx-2">
                      <label class="custom-checkbox">
                        <input  v-model="checked" :value="cart.id" type="checkbox">
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <div class="img-card">
                      <img v-lazy="cart.product.image" alt="" />
                    </div>
                    <div class="product-card-information">
                      <div class="product-title">
                        <Link :href="route('product.show',cart.product.id)"><h2>{{cart.product['name_'+$page.props.locale]}}</h2></Link>
                        <div class="card-deleted">
                          <a @click="deleteCartItem(cart.product_id)" href="javascript:void(0)">
                            <svg
                              xmlns="http://www.w3.org/2000/svg"
                              width="24"
                              height="24"
                              viewBox="0 0 24 24"
                              fill="none"
                            >
                              <path
                                d="M21 5.24902H17.441C16.902 5.24902 16.425 4.90502 16.255 4.39502L15.939 3.44604C15.7 2.73004 15.033 2.25 14.279 2.25H9.71997C8.96597 2.25 8.299 2.73002 8.06 3.44702L7.74402 4.39502C7.57402 4.90602 7.09698 5.25 6.55798 5.25H3C2.586 5.25 2.25 5.586 2.25 6C2.25 6.414 2.586 6.75 3 6.75H4.29797L5.065 18.25C5.196 20.213 6.83901 21.75 8.80701 21.75H15.194C17.161 21.75 18.805 20.212 18.936 18.25L19.703 6.75H21C21.414 6.75 21.75 6.414 21.75 6C21.75 5.586 21.414 5.24902 21 5.24902ZM9.48297 3.92004C9.51697 3.81804 9.61197 3.74902 9.71997 3.74902H14.279C14.387 3.74902 14.482 3.81807 14.516 3.91907L14.832 4.86804C14.876 5.00004 14.931 5.12705 14.993 5.24805H9.005C9.067 5.12605 9.12202 5.00007 9.16602 4.86707L9.48297 3.92004ZM17.438 18.149C17.36 19.327 16.374 20.249 15.193 20.249H8.80603C7.62603 20.249 6.63897 19.327 6.56097 18.149L5.80103 6.74902H6.55798C6.66498 6.74902 6.77 6.736 6.875 6.724C6.917 6.731 6.95502 6.74902 6.99902 6.74902H16.999C17.043 6.74902 17.081 6.731 17.123 6.724C17.228 6.736 17.332 6.74902 17.44 6.74902H18.197L17.438 18.149ZM14.75 10.999V15.999C14.75 16.413 14.414 16.749 14 16.749C13.586 16.749 13.25 16.413 13.25 15.999V10.999C13.25 10.585 13.586 10.249 14 10.249C14.414 10.249 14.75 10.585 14.75 10.999ZM10.75 10.999V15.999C10.75 16.413 10.414 16.749 10 16.749C9.586 16.749 9.25 16.413 9.25 15.999V10.999C9.25 10.585 9.586 10.249 10 10.249C10.414 10.249 10.75 10.585 10.75 10.999Z"
                                fill="black"
                              />
                            </svg>
                          </a>
                        </div>
                      </div>
                      <div class="card-details">
                        <h3>{{ __('Brand') }} : <span> {{ cart.product.brand?cart.product.brand.translated_name:null }} </span></h3>
                        <div class="d-flex gap-2 align-items-center">
                          <div>{{ __('Quantity') }} : <br>
                             <!-- <input @keyup="update_quantity(cart)" class="sm-input" type="number" v-model="cart.quantity"> -->
                             <div class="number-input">
                              <button @click="minus(cart)">-</button>
                              <input type="number" disabled id="quantity" v-model="cart.quantity">
                              <button @click="plus(cart)">+</button>
                            </div>
                          </div>
                          <div v-if="cart.product.sizes && cart.product.sizes.length>0">{{ __('Size') }} : <br>
                            <select class="custom-select"  @change="update_size_color(cart)" v-model="cart.size" >
                              <option v-show="size.pivot.inStock" :value="size.name[currentLang]" v-for="size,index in cart.product.sizes" :key="index"> {{size.name[currentLang]}} </option>
                            </select>
                          </div>
                          <div v-if="cart.product.colors && cart.product.colors.length>0" >{{ __('Color') }} : <br>
                            <select class="custom-select"  @change="update_size_color(cart)" v-model="cart.color" >
                              <option :value="color.translated_name" v-for="color,index in cart.product.colors" :key="index"> {{ color.translated_name }} </option>
                            </select>
                          </div>
                        </div>
                        <h2 class="price-tag">{{cart.product.final_selling_price}} {{ __('SAR') }} </h2>
                        <div class="order-date d-flex align-items-center gap-1">
                          <h4 class="d-flex align-items-center gap-2">
                            <img src="/home/img/package-black.svg" alt="" />
                            {{ __('Delivery by') }} :
                          </h4>
                          <h4 class="date">26 Jan - 28 Jan</h4>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5">
              <div class="order-details">
              <order-details ref="orderDetails" :carts="carts"></order-details>
              <div class="order-button mt-3">
              <button @click="next()" :disabled="checked.length==0">{{__('Proceed to Checkout')}}</button>
              </div>
              </div>
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
            <h3>{{carts.length}} {{ __('Item') }}</h3>
          </div>
          <div class="card-content" v-for="cart,index in carts" :key="index">
            <div class="d-flex align-items-center">
              <div class="img-card">
                <img v-lazy="cart.product.image" alt="" />
              </div>
              <div class="card-content-dis">
                <h4>{{cart.product['name_'+$page.props.locale]}}</h4>
                <h5>{{__('Quantity')}}: <span>{{cart.quantity}}</span></h5>
                <div class="d-flex gap-2" v-if="cart.product.sizes && cart.product.sizes.length>0">
                    <span>{{ __('Size') }} : </span>
                    <select class="custom-select"  @change="update_size_color(cart)" v-model="cart.size" >
                      <option :value="size.name[currentLang]" v-for="size,index in cart.product.sizes" :key="index">  {{size.name[currentLang]}}</option>
                    </select>
                  </div>
                  <div class="d-flex gap-2" v-if="cart.product.colors && cart.product.colors.length>0" >
                    <span>{{ __('Color') }} : </span>
                    <select class="custom-select"  @change="update_size_color(cart)" v-model="cart.color" >
                      <option :value="color.translated_name" v-for="color,index in cart.product.colors" :key="index"> {{ color.translated_name }} </option>
                    </select>
                  </div>
                <h5>{{ __('Brand') }} : <span> {{cart.product.brand?cart.product.brand.translated_name:null}}</span></h5>
              </div>
            </div>
            <div class="">
              <div class="order-date d-flex align-items-center gap-1">
                <h4 class="d-flex align-items-center gap-2">
                  <img src="/home/img/package-black.svg" alt="" />
                  {{ __('Delivery by') }} :
                </h4>
                <h4 class="date">26 Jan - 28 Jan</h4>
                <h2 class="price"> {{exchange_price(cart.product.final_selling_price,'SAR')}} {{__('SAR')}}</h2>
              </div>
            </div>
          </div>

          <!-- <div class="coupon-container">
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
          </div> -->

          <div class="order-details-res">
            <div class="title">
              <h3>{{__('Order Details')}}</h3>
            </div>
            <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>{{__('Subtotal')}}</p>
              <p> {{ get_subtotal() }}  {{__("SAR")}}</p>
            </div>
            <!-- <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>Coupon savings</p>
              <a href="#" class="coupon">Add Coupon</a>
            </div> -->
            <div
              class="d-flex price-dec align-items-center justify-content-between"
            >
              <p>{{__('Shipping Fee')}}</p>
              <p>{{shipping}} {{ __('SAR') }}</p>
            </div>
            <div class="total-price-check">
              <div class="d-flex justify-content-between align-items-center">
                <h4>{{__('Total Amount')}}:</h4>
                <div class="d-flex align-items-center gap-3">
                  <h3>{{ get_total().toFixed(2) }} {{ __('SAR') }}</h3>
                </div>
              </div>
              <div class="mt-3">
              <button @click="next()" :disabled="checked.length==0" class="w-100 site-btn">{{__('Proceed to Checkout')}}</button>
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
            <div class="pt-2">
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
    import Form from 'vform'

  export default {
      components: { App, ProductBox, OrderDetails },
      props:{
        carts:Array,
        colors:Array,
        sizes:Array,
      },
      mounted(){
        this.carts.forEach((el)=>{
          if(el.selected==1){
            this.checked.push(el.id)
          }
        })
      },
      data(){
        return {
          form:useForm({
            id:null,
            product_id:null,
            option_id:null,
            quantity:0,
            size:null,
            color:null,
          }),
          vform:new Form({
            checked:[]
          }),
          shipping:0,
          checked:[]
        }
      },
      methods:{
        next(){
          this.vform.checked=this.checked
          this.vform.post((route('cart.select_items')))
              .then((resp)=>{
                this.$inertia.visit(route('cart.checkout.address'))
              })
        },
        update_size_color(cart){
          this.form.id=cart.id
          this.form.product_id = cart.product_id
          this.form.size = cart.size
          this.form.color = cart.color
          this.form.post(route('cart.update_size_color'))
        },
        update_quantity(cart){
          this.form.id=cart.id
          this.form.product_id = cart.product_id
          this.form.quantity=cart.quantity
          this.form.post(route('cart.update_quantity'),{
            onSuccess:()=>{
              this.handleQuantityUpdated()
            }
          })
          
        },
        handleQuantityUpdated(){
          if (this.$refs.orderDetails) {
            this.$refs.orderDetails.calculate_order();
          } else {
            console.error('OrderDetails component is not available');
          }
        },  
        minus(cart){
          if(cart.quantity>1){
            cart.quantity-=1
            this.update_quantity(cart)
          }
        },
        plus(cart){
          if(cart.quantity<10){
            cart.quantity=parseInt(cart.quantity)+1
            this.update_quantity(cart)
          }
        },
        check_cart_attribute(cart,attribute_id){
          let attributes = cart.attributes
          let attr= attributes.find((e)=>e.id==attribute_id)
          return attr
        },
        get_option(options,option_id){
          if(options.length>0){
            return options.find((e)=>e.id==option_id)
          }
        },
        remove_attr(cart,attribute_id){
          let attributes = cart.attributes
          let attr= attributes.find((e)=>e.id==attribute_id)
          let index = attributes.indexOf(attr)
          cart.attributes.splice(index,1)

        },
        get_color(id,name){
          let color = this.colors.find((e)=>e.id==id)
          if(color)
          return color[name]
        },
        get_size(id,name){
          let size = this.sizes.find((e)=>e.id==id)
          if(size)
          return size[name]
        },

        get_subtotal(){
                let Subtotal = 0
                this.carts.forEach((el)=>{
                    Subtotal+=parseFloat(el.product.final_selling_price*el.quantity)
                })
                return this.exchange_price(Subtotal,'SAR')
            },
            get_total(){
                return this.get_subtotal() - (this.get_subtotal()*this.get_carts_discount_coupon()/100) + this.shipping
            },
            get_shipping(){
                axios.get('/get_shipping_price')
                     .then((resp)=>{
                        this.shipping=parseInt(resp.data)
            })
            },
            get_carts_discount_coupon(){
                if(this.$page.props.auth.user.cart_discount)
                return this.$page.props.auth.user.cart_discount.discount_percentage
                else
                return 0
            },

      },
      computed: {
            checkAll: {
            get: function () {
                return this.carts ? this.checked.length == this.carts.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.carts.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

  }

  </script>

<style>
.emty-card img{margin: 0 auto;height: 300px;
    width: auto;}
  .sm-input{width:100px  ;  }

.selected-attribute{padding: 4px 10px;
    display: block;
    cursor: pointer;
    border: 2px solid #9600c1;
    border-radius: 9px;
    text-align: center;}

.number-input input[type=number] {
  width: 60px;
  text-align: center;
}

/* Style the plus and minus buttons */
.number-input button {
  width: 30px;
  height: 40px;
  border: none;
  background-color: #f1f1f1;
  cursor: pointer;
}

.number-input button:hover {
  background-color: #ddd;
}

.number-input button:active {
  background-color: #ccc;
}
.number-input {
    width: 100%;
    border: 2px solid #e4e4e4;
    border-radius: 13px;
    justify-content: start;
    padding: 0 !important;
    display: flex;
    align-items: center;
    background: #fafafa;
    overflow: hidden;
    height: auto;
  }
  .custom-select{border-radius: 10px;cursor: pointer}

</style>


