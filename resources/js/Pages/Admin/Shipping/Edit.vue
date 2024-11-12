<template>

    <auth-layout>
      <div class="container">
        <page-header></page-header>

      <div class="row mb-5">
          <div class="col-xl-12">
              <div class="card custom-card">

            <div class="p-sm-3 p-0">
            <form @submit.prevent="update()">
        <div class="row">

            <div class="col-lg-12">
                <div class="mb-3">
                    <label  class="form-label"> {{__('price')}} </label>
                    <input :class="{ 'is-invalid': errors.price } " v-model="form.price" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                    <div class="text-danger" v-if="errors.price" v-html="errors.price" />
                </div>
                </div>
                <div class="col-lg-12">
                <div class="mb-3">
                    <label  class="form-label"> {{ __('free shipping start at') }} </label>
                    <input type="date" :class="{ 'is-invalid': errors.free_shipping_start_at } " v-model="form.free_shipping_start_at" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                    <div class="text-danger" v-if="errors.free_shipping_start_at" v-html="errors.free_shipping_start_at" />
                </div>
                </div>
                <div class="col-lg-12">
                <div class="mb-3">
                    <label  class="form-label"> {{ __('free shipping end at') }} </label>
                    <input type="date" :class="{ 'is-invalid': errors.free_shipping_end_at } " v-model="form.free_shipping_end_at" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                    <div class="text-danger" v-if="errors.free_shipping_end_at" v-html="errors.free_shipping_end_at" />
                </div>
                </div>
                <div class="col-lg-12">
                <div class="mb-3">
                    <label  class="form-label"> {{ __('free shipping start at amount') }} (SAR) </label>
                    <input type="number" :class="{ 'is-invalid': errors.free_shipping_start_at_amount } " v-model="form.free_shipping_start_at_amount" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                    <div class="text-danger" v-if="errors.free_shipping_start_at_amount" v-html="errors.free_shipping_start_at_amount" />
                </div>
                </div>

            <div class="col-12">
            <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                <button :disabled="form.processing" type="submit" class="btn btn-primary m-1"> <i class="ri-save-line"></i> {{__('save')}} </button>
            </div>
            </div>
        </div>
        </form>
            </div>
        </div>


          </div>
      </div>

      </div>


    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm, router  } from '@inertiajs/vue3';
export default {
  components: { AuthLayout, PageHeader },
  props:{
        errors:Object,
        shipping:Object
    },
  data(){
        return {
            form:useForm({
                id:this.shipping.id,
                price:this.shipping.price,
                free_shipping_start_at:this.shipping.free_shipping_start_at,
                free_shipping_end_at:this.shipping.free_shipping_end_at,
                free_shipping_start_at_amount:this.shipping.free_shipping_start_at_amount
            }),

        }
    },
    methods:{
      update(){
            this.form.post(route('shipping.update'))
        },


    }

  }
</script>

