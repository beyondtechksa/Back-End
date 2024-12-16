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
            <div class="cart-container cart-page" v-if="carts.length">
                <div class="container-cum">
                    <form @submit.prevent="pay()">
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
                                                <img src="/home/img/paymentg.png" alt="" />
                                                <p>{{__('Payment')}}</p>
                                            </div>
                                        </div>

                                        <div class="payment-method">
                                            <div class="">
                                                <!-- <a href="#" @click="loadPaymentIframe('paypal')"> PayPal </a>
                                                <a href="#" @click="loadPaymentIframe('credit')">
                                                    <img src="/home/img/Mathod.png" alt="" />
                                                </a>
                                                <a href="#" @click="loadPaymentIframe('tabby')">
                                                    <img src="/home/img/mathod1.png" alt="" />
                                                </a>-->
                                                <a href="#" @click="loadPaymentIframe('tamara')">
                                                    <img src="/home/img/tamara.png" alt="" />
                                                </a>
                                                <a href="#" @click="loadPaymentIframe('clickPay')">
                                                    <img src="/home/img/ClickPayLogo.png" alt="" />
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Conditionally show the Tamara payment iframe -->

                                        <!-- ====== Response method  ===== -->
                                        <div class="response-method">
                                            <!-- <a href="#"> PayPal </a>
                                            <a href="#" class="active"> Card </a>
                                            <a href="#">
                                                <img src="/home/img/mathod4.png" alt="" />
                                            </a> -->
                                            <a href="#">
                                                <img src="/home/img/mathod65.png" alt="" />
                                            </a>
                                        </div>
                                         <iframe v-if="showTamaraFrame" id="tamara" v-lazy="paymentUrl" sandbox="allow-forms allow-modals allow-popups-to-escape-sandbox allow-popups allow-scripts allow-top-navigation allow-same-origin"></iframe>

                                        <iframe v-if="showIFrame"      id="telr"   :src="paymentUrl" sandbox="allow-forms allow-modals allow-popups-to-escape-sandbox allow-popups allow-scripts allow-top-navigation allow-same-origin"></iframe>

                                        <!-- Telr iframe (hidden by default) -->

                                        <!-- ==== Responsive Check out form ===== -->
                                        <div class="responsive-check-out">
                                            <form action="" class="row">
                                                <div class="input-box col-6">
                                                    <input type="email" :placeholder="__('Name on the card')" />
                                                </div>
                                                <div class="input-box col-6">
                                                    <input type="text" :placeholder="__('Card Number')" />
                                                </div>

                                                <div class="number-box col-12">
                                                    <div class="number-code">
                                                        <p>CVV</p>
                                                    </div>
                                                    <div class="number-input">
                                                        <input type="text" :placeholder="__('Expiry Date')" />
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
                                    <div class="mt-4 order-button">
                                        <!-- Pay Now button -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ======= Empty Cart ======= -->
            <div class="emty-card" v-else>
                <div class="card-img">
                    <div class="">
                        <img class="" src="/home/img/emty.jpg" alt="" />
                    </div>
                </div>
            </div>

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
    props: {
        carts: Array,
        frame: String  // Tamara payment iframe URL coming from the backend
    },
    data() {
        return {
            showTamaraFrame: false,  // Control the visibility of the iframe
            showIFrame: false,  // Control the visibility of the iframe
            paymentUrl: null,   // URL for the payment iframe
            form: useForm({}),
            url : null,
            token : null,
            user_id : null,
        }
    },
    methods: {
        pay() {
            this.form.post(route('order.create'))
        },
        loadPaymentIframe(method) {
            if(method == 'clickPay'){
                axios.get('payment/click-pay').then(res => {
                 console.log("we are in tamara");
                //  this.paymentUrl = res.data.frame ;
                //  this.showIFrame = true;
                window.open(res.data.frame, '_blank');
                }).catch(err => {
                    console.log(err)
                })
            }else if(method == 'tamara'){
                axios.get('/cart/checkout/tamara/payment').then(res => {
                 this.paymentUrl = res.data.frame;
                 //this.showIframe = false;
                 this.showIFrame = true;
                 this.url = res.data.url;
                 this.token = res.data.token;
                 this.user_id = res.data.user_id;
                console.log('cart/checkout/tamara/payment');
                console.log(this.paymentUrl );
                console.log(res.data );
                }).catch(err => {
                    console.log(err)
                })
            }
            else{
                this.paymentUrl = this.frame;
                this.showTamaraFrame = false;
                this.showIFrame = true;
            }
        },
        async callApi() { // Add `async` here

        if (this.url != null && this.token != null ) {
            const headers = {
                accept: 'application/json',
                authorization: this.token, // Ensure `this.token` is set correctly
            };

            try {
                const response = await fetch(this.url, { // Use await here
                    method: 'POST',
                    headers: headers,
                });

                if (!response.ok) {
                    console.log("it is inprogress");
                }

                const data = await response.json();
                if (data.status == "authorised") { // Adjust based on your API response structure
                     window.location.href  = `/tamara/order_success/${this.user_id}`;

                }
            } catch (error) {
                console.error('Error fetching API:', error);
            }
        }
    },
        startApiCalls() {
            this.intervalId = setInterval(this.callApi, 5000); // Call API every 5 seconds
        },
        stopApiCalls() {
            if (this.intervalId) {
                clearInterval(this.intervalId); // Stop the interval
                this.intervalId = null;
            }
        },
    },
    mounted() {
        this.startApiCalls(); // Start the interval when the component is mounted
    },
    beforeDestroy() {
        this.stopApiCalls(); // Clear the interval if the component is destroyed
    },
}
</script>

<style>
.emty-card img { margin: 0 auto; }
.input-box { width: 100%; }
#tamara {
    width: 100%;
    min-width: 600px;
    height: 600px;
    border: 0;
}
#telr {
    width: 100%;
    min-width: 600px;
    height: 600px;
    border: 0;
}

</style>
