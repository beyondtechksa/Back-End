<template>

        <div class="order-content">
            <div class="title-order">
            <h2> {{ __('Order Details') }} </h2>
            </div>
            <div class="total-price">
            <h4>{{ __('Subtotal') }}</h4>
            <h4 class="price-tag-e"> {{ __('SAR') }} {{ data.subtotal }}</h4>
            </div>
            <div class="total-price">
            <h4>{{ __('Subtotal before vat') }}</h4>
            <h4 class="price-tag-e"> {{ __('SAR') }} {{ data.subtotalBeforeVat }}</h4>
            </div>
            <div class="total-price">
                <h4>{{ __('Vat') }}</h4>
                <h4 class="price-tag-e">{{ __('SAR') }} {{ data.vat }}</h4>
            </div>

            <div class="total-price">
            <h4>{{ __('Shipping Fee') }} (<span class="fs-16 text-success"> {{__('including vat')}} </span>) </h4>
            <h4 class="price-tag-e">{{ __('SAR') }} {{ data.shipping }}</h4>
            </div>

            <div class="total-price" style="border-top:1px solid #ddd">
                <h4>{{ __('Total Before Discount') }}</h4>
                <h4 class="price-tag-e"> {{data.totalBeforeDiscount}}</h4>
            </div>

            <div class="total-price">
                <h4>{{ __('Discount') }}</h4>
                <h4 class="price-tag-e">% {{data.cart_discount_coupon}}</h4>
            </div>
            <div v-show="$page.props.auth.user">
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
            <h3 class="Total-amount">{{'SAR'}} {{ data.total }}</h3>
            </div>
        </div>

</template>


<script>
import { useForm } from '@inertiajs/vue3'
import Form from 'vform';
    export default {
        props:{carts:Array},
        data(){
            return {
                form:useForm({
                    code:null,

                }),
                vform:new Form({
                    carts:this.carts
                }),
                data:{},
            }
        },
        mounted(){
                this.calculate_order()
        },
        computed: {

        },
        methods:{

            calculate_order(){
                this.vform.post('/calculate_order')
                     .then((resp)=>{
                        this.data=resp.data
            })
            },
            add_coupon(){
                this.form.post(route('cart.add_coupon'),{
                    onSuccess : ()=>{
                        this.calculate_order()
                    }
                })
            }

        }
    }

</script>


<style >
    .fs-16{font-size: 16px;}
</style>
