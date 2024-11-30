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
                                    <h3>{{ __('Return Order') }}</h3>
                                    <!-- <p>{{__('Enter your order Id getting from your order page')}}</p> -->
                                </div>

                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="step-preview">
                                                <div class="step-header">
                                                    <h4> 1- {{ __('product details') }} </h4>
                                                </div>
                                                <div class="product-card" v-for="order_item, index in order.order_items"
                                                    :key="index">
                                                    <div class="d-flex align-items-center gap-3">

                                                        <div class="img-card" style="height: 100px;width: auto;">
                                                            <img v-lazy="order_item.product.image" alt="" />
                                                        </div>
                                                        <div class="product-card-information">
                                                            <div class="product-title">
                                                                <Link
                                                                    :href="route('product.show', order_item.product.id)">
                                                                <p class="fw-bold"> {{ order_item.product['name_' + $page.props.locale]
                                                                    }} </p>
                                                                </Link>
                                                            </div>
                                                            <div class="card-details">
                                                                <p>
                                                                    {{ __('Color') }} : <span> {{
                                                                        order_item.translated_color }} </span> / {{
                                                                    __('Quantity') }} :
                                                                    <span>{{ order_item.quantity }}/</span> {{ __('Size')
                                                                    }} : <span>{{ order_item.translated_size }}</span>
                                                                </p>
                                                                <p class="price-tag fs-18">{{ __('SAR') }} {{
                                                                    order_item.price }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="">
                                                <p class="border-bottom mb-2 fs-18 pb-1"> {{ __('return reason') }} </p>
                                                    <ul class="px-2">
                                                        <li class="fs-14">- {{ order.return_request.return_reason?order.return_request.return_reason.translated_name:null }} </li>
                                                        <li v-if="order.return_request.reason" class="fs-14">- {{ order.return_request.reason }} </li>
                                                    </ul>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="step-preview">
                                                <div class="step-header">
                                                    <h4> 1- {{ __('order details') }} </h4>
                                                </div>
                                                <div class="step-body">
                                                <div class="d-flex justify-content-between pb-1 pt-3 border-bottom">
                                                    <strong> {{ __('order Id') }} </strong>
                                                    <span> #{{ order.id }} </span>
                                                </div>
                                                <div class="d-flex justify-content-between pb-1 pt-3 border-bottom">
                                                    <strong> {{ __('purchased date') }} </strong>
                                                    <span> {{ formateDate(order.created_at) }} </span>
                                                </div>
                                                <div class="pt-3">
                                                    <p> <strong>{{ __('products') }} </strong> </p>
                                                    <p class="fs-14" v-for="order_item,index in order.order_items" :key="index">
                                                       - {{ order_item.product['name_' + $page.props.locale] }}
                                                    </p>
                                                </div>
                                                </div>
                                            </div>

                                            <div class="info-box pt-4">
                                            <p class="fw-bold"> {{__('return request status')}} </p>
                                            <div class="box-body">
                                            <p> {{ order.return_request.return_status?order.return_request.return_status.translated_name:null }} </p>
                                            </div>
                                            </div>

                                            <div class="info-box pt-4">
                                            <p class="fw-bold"> {{__('return options')}} </p>
                                            <div class="box-body">
                                                <div class="selector mb-2">
                                                    <div
                                                        class="d-flex align-items-center gap-1">
                                                        <input  v-model="form.gender"
                                                        type="radio"
                                                        id="Male"
                                                        name="fav_language"
                                                        value="cache"
                                                        />
                                                          <label for="cache">{{__('cache')}}</label>
                                                    </div>
                                                </div>
                                                <div class="selector mb-2">
                                                    <div
                                                        class="d-flex align-items-center gap-1">
                                                        <input  v-model="form.gender"
                                                        type="radio"
                                                        id="Male"
                                                        name="fav_language"
                                                        value="product_replace"
                                                        />
                                                          <label for="product_replace">{{__('product replace')}}</label>
                                                    </div>
                                                </div>
                                                <div class="selector mb-2">
                                                    <div
                                                        class="d-flex align-items-center gap-1">
                                                        <input  v-model="form.gender"
                                                        type="radio"
                                                        id="Male"
                                                        name="fav_language"
                                                        value="wallet_balance"
                                                        />
                                                          <label for="wallet_balance">{{__('wallet balance')}}</label>
                                                    </div>
                                                </div>

                                            </div>
                                            </div>
                                            <div class="info-box pt-4">
                                            <p class="fw-bold"> {{__('shipping methods')}} </p>
                                            <div class="box-body">
                                                <div class="selector mb-2">
                                                    <div
                                                        class="d-flex align-items-center gap-1">
                                                        <input  v-model="form.gender"
                                                        type="radio"
                                                        id="Male"
                                                        name="fav_language"
                                                        value="product_return_to_store"
                                                        />
                                                          <label for="product_return_to_store">{{__('product return to store')}}</label>
                                                    </div>
                                                </div>
                                                <div class="selector mb-2">
                                                    <div
                                                        class="d-flex align-items-center gap-1">
                                                        <input  v-model="form.gender"
                                                        type="radio"
                                                        id="Male"
                                                        name="fav_language"
                                                        value="shipping_company"
                                                        />
                                                          <label for="shipping_company">{{__('shipping company')}}</label>
                                                    </div>
                                                </div>


                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </page-content>

                    </div>
                </div>
            </div>



        </main>
    </app>

</template>



<script>
import App from '@/HomeLayouts/AuthenticatedLayout.vue';
import SideBar from './Components/SideBar.vue';
import PageContent from './Components/PageContent.vue';
import { useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

export default {
    components: { App, SideBar, PageContent },
    props: {
        order: Object,
    },
    data() {
        return {
            form: useForm({
                order_id: this.order.id,
            })
        }
    },
    methods: {
        return_request() {
            this.form.post(route('profile.store_return_request'))
        },
        upload(e) {
            let file = e.target.files[0]
            this.form.image = file
            const reader = new FileReader()
            reader.onload = () => {
                this.form.image_base = reader.result
            }
            reader.readAsDataURL(file)
        },
        cancel_return() {
            Swal.fire({
                title: this.__('Alert !'),
                text: this.__('you will not be able to restore the item or the mony'),
                icon: "warning",
                showCancelButton: false,
                confirmButtonColor: "#390049",
                cancelButtonColor: "#afafaf",
                confirmButtonText: this.__('Yes, cancel'),
            }).then((result) => {
                if (result.value) {
                    this.form.post(route('profile.return_item.cancel'))
                }
            });
        }
    }
}
</script>
