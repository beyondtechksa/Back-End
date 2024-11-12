

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
                    <h3>{{__('Return Order')}}</h3>
                    <!-- <p>{{__('Enter your order Id getting from your order page')}}</p> -->
                </div>
                <div v-if="order.order_items.length>0">
                <div class="product-card" v-for="order_item,index in order.order_items" :key="index">
                    <div class="d-flex align-items-center gap-3">
                        <div class="selection mx-2">
                            <label class="custom-checkbox">
                                <input  v-model="form.items" :value="order_item.id" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            </div>

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

                <div class="my-5">
                    <form class="user-forminput" @submit.prevent="return_request()">
                    <div class="mb-3">
                        <label class="form-label"> {{ __('reason of return') }} </label>
                        <input :placeholder="__('enter the resaon of return')" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> {{ __('select reason') }} </label>
                        <select class="form-control" v-model="form.return_reason_id">
                            <option :value="return_reason.id" v-for="return_reason,index in return_reasons" :key="index"> {{ return_reason.translated_name }} </option>
                        </select>
                    </div>
                    <button :disabled="form.items.length==0"  class="track-order">{{ __('return request') }}</button>
                    </form>
                </div>
                </div>
                <div v-else>
                   <p class="pt-5 text-danger"> there is no items to return </p>
                </div>

                <div class="return-history account-title mt-5" v-if="order.return_requests.length>0">
                    <h3> {{__('return history')}} </h3>
                    <div class="product-card" v-for="return_item,index in order.return_requests[0].return_items" :key="index">
                    <div class="d-flex align-items-center gap-3">

                        <div class="img-card">
                        <img v-lazy="return_item.order_item.product.image" alt="" />
                        </div>
                        <div class="product-card-information">
                        <div class="product-title">
                            <Link :href="route('product.show',return_item.order_item.product.id)"><h2> {{ return_item.order_item.product['name_'+$page.props.locale] }} </h2></Link>
                        </div>
                        <div class="card-details">
                            <h3>{{__('Brand')}} : <span>{{return_item.order_item.product.brand.translated_name}}</span></h3>
                            <h3>
                                {{ __('Color') }} : <span> {{ return_item.order_item.translated_color }} </span> / {{ __('Quantity') }} :
                                <span>{{return_item.order_item.quantity}}/</span> {{ __('Size') }} : <span>{{ return_item.order_item.translated_size }}</span>
                            </h3>
                            <h2 class="price-tag">{{ __('SAR') }} {{ return_item.order_item.price }}</h2>
                            <h3>{{__('Status')}} : <span>{{return_item.return_status ? return_item.return_status.translated_name : null }}</span></h3>
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

export default{
  components: { App, SideBar, PageContent },
  props:{
    order:Object,
    return_reasons:Object,
},
  data(){
    return {
      form : useForm({
        order_id:this.order.id,
        return_reason_id:null,
        items:[]
    })
    }
  },
  methods:{
    return_request(){
        this.form.post(route('profile.store_return_request'))
    }
  }
}
</script>
