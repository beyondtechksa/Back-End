<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>

        <div class="row justify-content-center">
         <div class="col-md-10">  
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    {{ $page.props.page_name }} 
                </div>
                <div class="prism-toggle">
                    <button @click="back()" class="btn btn-sm btn-danger-light"> {{ __('cancel') }} <i class="ri-arrow-left-line ms-2 align-middle d-inline-block"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="edit">
               
                    <form-box :form="form" :errors="errors"></form-box>
                  
                    <div class="mb-3">
                        <div class="hstack gap-2">
                            <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
                        </div>
                    </div>
          
                </form>
            </div>
        </div>
        </div>
        </div> 
        </div>
        </auth-layout>
    </div>
</template>


<script>


import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import FormBox from './FormBox.vue';
export default {
  components: { AuthLayout, PageHeader, FormBox},
  props:{
    errors:Object,
    ShippingCompany:Object,
  },
  data(){
    return {
        form:useForm({
            name:this.ShippingCompany.name,
            email:this.ShippingCompany.email,
            password:this.ShippingCompany.password,
            phone:this.ShippingCompany.phone,
            address:this.ShippingCompany.address,
            note:this.ShippingCompany.note,
            logo:this.ShippingCompany.logo,
        }),
    }
  },
  methods:{
        edit(){
                this.form.put(route('shipping_companies.update',this.ShippingCompany.id))
            },
       
            
  },


}



</script>