<template>

        <div class="order-content">
            <div class="title-order">
            <h2> {{ __('Order Details') }} </h2>
            </div>
            <div class="total-price">
            <h4>{{ __('Subtotal') }}</h4>
            <h4 class="price-tag-e"> {{ __('SAR') }} {{ get_subtotal() }}</h4>
            </div>
            <div class="total-price">
            <h4>{{ __('Subtotal before vat') }}</h4>
            <h4 class="price-tag-e"> {{ __('SAR') }} {{ SubtotalBeforeVat }}</h4>
            </div>
            <div class="total-price">
                <h4>{{ __('Vat') }}</h4>
                <h4 class="price-tag-e">{{ __('SAR') }} {{ calculateVat }}</h4>
            </div>
            <div v-show="$page.props.auth.user">
            <div class="total-price">
            <h4>{{ __('Shipping Fee') }} (<span class="fs-16 text-success"> {{__('including vat')}} </span>) </h4>
            <h4 class="price-tag-e">{{ __('SAR') }} {{ shipping }}</h4>
            </div>

            <div class="total-price" style="border-top:1px solid #ddd">
                <h4>{{ __('Total Before Discount') }}</h4>
                <h4 class="price-tag-e"> {{totalBeforeDiscount}}</h4>
            </div>

            <div class="total-price">
                <h4>{{ __('Discount') }}</h4>
                <h4 class="price-tag-e">% {{get_carts_discount_coupon()}}</h4>
            </div>

            <div class="coupon-code">
            <form @submit.prevent="add_coupon()">
                <img src="/home/img/coupon.jpg" alt="" />
                <input v-model="form.code"
                type="text"
                :placeholder="__('Enter a Coupon or Promo code')"
                />
                <button :disabled="form.processing">{{ __('Add') }}</button>
            </form>
            <div class="text-danger" v-html="form.errors.code"></div>
            </div>
            </div>
            <div class="Amount d-flex justify-content-between">
            <h3>{{ __('Total Amount') }}</h3>
            <h3 class="Total-amount">{{'SAR'}} {{ get_total().toFixed(2) }}</h3>
            </div>
        </div>

</template>


<script>
import { useForm } from '@inertiajs/vue3'
    export default {
        props:{carts:Array},
        data(){
            return {
                shipping:0,
                form:useForm({
                    code:null
                })
            }
        },
        mounted(){
            this.get_shipping()
        },
        computed: {
            total(){
                return this.totalBeforeDiscount - (this.totalBeforeDiscount*this.get_carts_discount_coupon()/100)
            },
            totalBeforeDiscount(){
                return parseFloat(this.get_subtotal())+ parseFloat(this.shipping)
            },
            calculateVat(){
               let vat = 0
               this.carts.forEach((el)=>{
                let product=el.product
                let price=product.final_selling_price/(1+(product.tax_percentage/100))
                vat+=(product.final_selling_price-price)
               })
              return this.exchange_price(vat.toFixed(2),'SAR')
            },
            SubtotalBeforeVat(){
               let price_before_vat = 0
               this.carts.forEach((el)=>{
                let product=el.product
                let price=product.final_selling_price/(1+(product.tax_percentage/100))
                price_before_vat+=price
               })
              return  this.exchange_price(price_before_vat.toFixed(2),'SAR')

            },
        },
        methods:{
            get_subtotal(){
                let Subtotal = 0
                this.carts.forEach((el)=>{
                    Subtotal+=parseFloat(el.product.final_selling_price*el.quantity)
                })
                return this.exchange_price(Subtotal,'SAR')
            },
            get_total(){
                return   this.total
                // this.get_subtotal() - (this.get_subtotal()*this.get_carts_discount_coupon()/100) + this.shipping
            },
            get_shipping(){
                axios.get('/get_shipping_price')
                     .then((resp)=>{
                        this.shipping=parseInt(resp.data)
                        // if(this.shipping>0){
                        //     this.shipping = this.shipping+(this.vat*this.shipping/100)
                        // }
            })
            },
            get_carts_discount_coupon(){
                if(this.$page.props.auth.user.cart_discount)
                return this.$page.props.auth.user.cart_discount.discount_percentage
                else
                return 0
            },
            add_coupon(){
                this.form.post(route('cart.add_coupon'))
            }

        }
    }

</script>


<style >
    .fs-16{font-size: 16px;}
</style>
