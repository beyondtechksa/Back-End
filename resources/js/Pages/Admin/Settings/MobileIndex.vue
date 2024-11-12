<template>

    <auth-layout>
      <Head :title="__('profile')" />
      <div class="container">
        <div class="modal fade" id="newModal" tabindex="-1"
    aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img v-lazy="check_src()">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>


        <page-header></page-header>
        <div class="mb-3">
          <button type="button" @click="form.category_id=null" class="btn btn-primary me-2 my-2" :class="{'btn-success':form.category_id==null}">  Best Seller  </button>
          <button type="button" @click="form.category_id=category.id" class="btn btn-primary me-2 my-2" :class="{'btn-success':form.category_id==category.id}" v-for="category,index in categories" :key="index"> {{category.translated_name}} </button>
      </div>
        <ul class="list-group pb-3">
            <li v-if="form.category_id==null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Top Slider
                <span class="text-success">
                  <span> 250px </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='top_slider'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'top_slider','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
            <li
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Single Banner1
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='single_banner1'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'single_banner1','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
            <li
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Shop By Brand
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='shop_by_brand'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'shop_by_brand','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
            <li v-if="form.category_id==null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Shop By Section
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='shop_by'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'shop_by','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
              <li v-if="form.category_id==null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Single Banner2
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='single_banner2'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'single_banner2','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
              <li v-if="form.category_id==null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Single Banner3
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='single_banner3'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'single_banner3','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
              <li
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                     Banners1
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='banners1'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'banners1','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
              <li
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Banners2
                <span class="text-success">
                  <span> h:flexible </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='banners2'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'banners2','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>

              <!-- <li v-if="form.category_id==null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Small Banners
                <span class="text-success">
                  <span> 100px </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='small_banners'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'small_banners','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li> -->
              <li v-if="form.category_id!=null"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    category Banners
                <span class="text-success">
                  <span> 100px </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='category_banners'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'category_banners','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
              <li
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                    Search Banners
                <span class="text-success">
                  <span> 100px </span>
                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <button @click="slug='search_banners'" data-bs-toggle="modal" data-bs-target="#newModal" class="btn btn-sm btn-warning btn-icon">
                    <i class="ri-eye-line"></i>
                  </button>
                 <Link :href="route('mobile_settings.edit',{'slug':'search_banners','category_id':form.category_id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
        </ul>

      </div>


    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm  } from '@inertiajs/vue3';
export default {
  components: { AuthLayout, PageHeader },
  props:{
    categories:Array
    },
  data(){
        return {
            form:useForm({
                id:null,
                category_id:null
            }),
            slug:null
        }
    },
    methods:{
      update_setting_status(id){
        axios.post('/admin/update_setting_status/'+id)
      },
      check_src(){
        let slug=this.slug
        if(this.form.category_id==null){
          return '/uploads/banners/'+slug+'.png'
        }else{
          return '/uploads/banners/category/'+slug+'.png'
        }
      }

    }

  }
</script>

