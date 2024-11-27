

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
                <div class="return-steps">
                    <p>1-  {{ __('select') }}  <span class="highlight"> {{__('product')}} </span> </p>
                    <p>2-  {{ __('select') }}  <span class="highlight"> {{__('return reason')}} </span> </p>
                    <p>3-  {{ __('upload') }}  <span class="highlight"> {{__('product image')}} </span> {{ __("to insure from it's status") }} </p>
                </div>
                <div v-if="order.order_items.length>0">
                <div class="row">
                <div class="col-lg-6">
                <div class="step-preview">
                <div class="step-header">
                <h4> 1- {{ __('select') }} {{ __('product') }} </h4>
                </div>
                <div class="product-card" v-for="order_item,index in order.order_items" :key="index">
                    <div class="d-flex align-items-center gap-3">
                        <div class="selection mx-2">
                            <label class="custom-checkbox">
                                <input  v-model="form.items" :value="order_item.id" type="checkbox">
                                <span class="checkmark"></span>
                            </label>
                            </div>

                        <div class="img-card" style="height: 150px;width: auto;">
                        <img v-lazy="order_item.product.image" alt="" />
                        </div>
                        <div class="product-card-information">
                        <div class="product-title">
                            <Link :href="route('product.show',order_item.product.id)"><h2> {{ order_item.product['name_'+$page.props.locale] }} </h2></Link>
                        </div>
                        <div class="card-details">
                            <h3>
                            {{ __('Color') }} : <span> {{ order_item.translated_color }} </span> / {{ __('Quantity') }} :
                            <span>{{order_item.quantity}}/</span> {{ __('Size') }} : <span>{{ order_item.translated_size }}</span>
                            </h3>
                            <h2 class="price-tag">{{ __('SAR') }} {{ order_item.price }}</h2>
                        </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="step-preview">
                <div class="step-header">
                <h4> 2- {{ __('select') }} {{ __('return reason') }} </h4>
                </div>

                <div class="my-3">
                        <label class="form-label"> {{ __('reason of return') }} </label>
                        <input :placeholder="__('enter the resaon of return')" type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label"> {{ __('select reason') }} </label>
                        <select class="form-control" v-model="form.return_reason_id">
                            <option :value="return_reason.id" v-for="return_reason,index in return_reasons" :key="index"> {{ return_reason.translated_name }} </option>
                        </select>
                    </div>


                </div>

                </div>

                <div class="col-lg-6">
                    <div class="step-preview">
                <div class="step-header">
                <h4> 3- {{ __('upload') }} {{ __('product image') }} </h4>
                </div>

                <div class="img-preview" v-if="form.image_base">
                    <img :src="form.image_base">
                </div>

                <label for="upload" class="icon-upload"  v-else>
                    <div>
                    <i class="ri-upload-line"></i>
                    <p> {{__('press for upload file')}} </p>
                    </div>
                </label>
                <input class="d-none" id="upload" type="file" @change="upload">
                <div class="px-2">
                <label for="upload" class="custom-btn w-100 my-2"> {{__('select file')}} </label>
                <div class="text-danger" v-html="form.errors.image" />
                </div>

                </div>
                </div>

                </div>

                <div class="mt-3 text-center d-flex gap-3 justify-content-center">
                    <!-- <Link :href="route('profile.return_order_details',order.id)" class="custom-btn btn-lg fs-18">{{ __('order line') }}</Link> -->
                    <button @click="return_request()" :disabled="form.items.length==0"  class="custom-btn btn-lg fs-18">{{ __('return request') }}</button>
                </div>

                </div>
                <div v-else>
                   <p class="pt-5 text-danger"> {{__('there is no items to return')}} </p>
                </div>

                <div class="return-history account-title mt-5" v-if="order.return_request">
                    <h3> {{__('return history')}} </h3>
                    <div class="product-card" v-for="return_item,index in order.return_request.return_items" :key="index">
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
        items:[],
        image:null,
        image_base:null,
    })
    }
  },
  methods:{
    return_request(){
        this.form.post(route('profile.store_return_request'))
    },
    upload(e){
        let file = e.target.files[0]
        this.form.image=file
        const reader=new FileReader()
            reader.onload= ()=>{
                this.form.image_base=reader.result
            }
            reader.readAsDataURL(file)
    }
  }
}
</script>
