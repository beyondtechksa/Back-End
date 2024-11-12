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
                <form @submit.prevent="create">
                    <form-box :form="form" :errors="errors" :currencies="currencies"></form-box>
                    
                    
                    <div class="mb-3">
                        <div class="hstack gap-2">
                            <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('create')}}  </button>
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
  components: { AuthLayout, PageHeader, useForm, FormBox},
  props:{
    errors:Object,
    currencies:Array
  },
  data(){
    return {
        form:useForm({
            name:null,
            note:null,
            discount_percentage_selling_price:0,
            packaging_shipping_fees:0,
            management_fees:0,
            profit_percentage:0,
            commercial_percentage:0,
            manual_price_adjustment:0,

        })
    }
  },
  methods:{
        create(){
            this.form.post(route('formulas.store'))
        },
       
  }

}



</script>