

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

                <div class="container">
                    <div class="row" v-if="return_item.status=='pending'">
                    <div class="col-md-5">
                    <div class="wrapper">
                        <ul class="sessions">
                        <li v-for="return_status,index in return_statuses" :key="index" :class="{'completed':return_item.return_status_id>=return_status.id}">
                            <p> {{return_status.translated_name}} </p>
                        </li>
                        </ul>
                    </div>
                    </div>
                    <div class="col-md-7">
                        <div class="step-preview">
                            <div class="step-header">
                                <h3 class="fs-18"> {{__('return data')}} </h3>
                            </div>
                            <div class="d-flex justify-content-between pt-3 px-3">
                                <span s="fs-16"> {{ __('product price') }} </span>
                                <span class="theme-text"> {{ order_item.price }} {{__('SAR')}} </span>
                            </div>
                        </div>
                        <div class="step-preview">
                            <div class="step-header">
                                <h3 class="fs-18"> {{__('return options')}} </h3>
                            </div>
                            <div class=" pt-3 px-3">
                                <div class="selection mx-2 mb-3">
                                  <label class="custom-checkbox">
                                      <input  v-model="form.return_funds" :value="'wallet'" type="radio" class="d-none">
                                      <span class="checkmark">  </span>
                                      <span class="mx-2">{{ __('Wallet') }} </span>
                                  </label>
                              </div>
                                <!-- <div class="selection mx-2">
                                  <label class="custom-checkbox">
                                      <input  v-model="form.return_funds" :value="'cash'" type="radio" class="d-none">
                                      <span class="checkmark">  </span>
                                      <span class="mx-2">{{ __('Cash on visa') }} </span>
                                  </label>
                              </div> -->
                            </div>
                        </div>
                        <!-- <button class="custom-btn mt-3"> {{ __('save changes') }} </button> -->
                        <div class="step-preview">
                            <div class="step-header">
                                <h3 class="fs-18"> {{__('manage your return')}} </h3>
                            </div>
                            <div class="pt-3 px-3">
                                <ul class="list-unstyled return-managment">
                                    <li> <button @click="cancel_return()" class="d-block"> {{ __('cancel the return') }} </button>  </li>
                                    <li> <Link class="d-block"> {{ __('show order details') }} </Link>  </li>
                                    <li> <Link :href="route('product.show',order_item.product_id,order_item.product.slug)" class="d-block"> {{ __('review the product') }} </Link>  </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div v-else class="mt-5 text-danger">
                        {{ __('You have canceled this return item') }}
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

export default{
  components: { App, SideBar, PageContent },
  props:{
    order_item:Object,
    return_item:Object,
    return_statuses:Object,
},
  data(){
    return {
      form : useForm({
        order_item_id:this.order_item.id,
        return_item_id:this.return_item.id,
        return_funds:this.return_item.return_option
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
    },
    cancel_return(){
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
