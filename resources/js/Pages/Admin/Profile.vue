<template>

    <auth-layout>
      <Head :title="__('profile')" />
      <div class="container">

      <!-- Page Header -->
      <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
          <h1 class="page-title fw-semibold fs-18 mb-0">{{__('dashboard')}}</h1>
          <div class="ms-md-1 ms-0">
              <nav>
                  <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item"><Link :href="route('admin.dashboard')">{{__('dashboard')}}</Link></li>
                      <li class="breadcrumb-item active" aria-current="page">{{__('profile')}}</li>
                  </ol>
              </nav>
          </div>
      </div>
      <!-- Page Header Close -->

      <div class="row mb-5">
          <div class="col-xl-12">
              <div class="card custom-card">
                  <div class="card-header d-sm-flex d-block">
                      <ul class="nav nav-tabs nav-tabs-header mb-0 d-sm-flex d-block" role="tablist">
                          <li class="nav-item m-1">
                              <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                              href="#personal-info" aria-selected="true">{{__('personal information')}}</a>
                          </li>

                          <li class="nav-item m-1">
                              <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                              href="#security" aria-selected="true">{{__('security')}}</a>
                          </li>
                      </ul>
                  </div>
                      <div class="tab-content">
                          <div class="tab-pane show active" id="personal-info"
                              role="tabpanel">
                              <div class="p-sm-3 p-0">
                                <form @submit.prevent="update_profile()">
                            <div class="row">
                                <div class="col-lg-12">
                                  <div class="mb-3 text-center">
                                        <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <input @input="upload" id="profile-img-file-input" type="file" class="profile-img-file-input">
                                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                            <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src()" class="rounded-circle avatar-xl img-thumbnail user-profile-image " alt="user-profile-image"></div>
                                            <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src()" class="rounded-circle avatar-xl img-thumbnail user-profile-image " alt="user-profile-image"> </div>
                                            <div class="camera p-0 rounded-circle">
                                                    <span class="avatar-title rounded-circle text-body">
                                                        <i class="ri-camera-line"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </label>

                                        <div class="text-danger" v-html="errors.avatar" />
                                    </div>

                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{__('name')}} </label>
                                        <input :class="{ 'is-invalid': errors.name } " v-model="form.name" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                        <div class="text-danger" v-if="errors.name" v-html="errors.name" />
                                    </div>
                                    </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{ __('email') }} </label>
                                        <input type="email" :class="{ 'is-invalid': errors.email } " v-model="form.email" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                        <div class="text-danger" v-if="errors.email" v-html="errors.email" />
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

                          <div class="tab-pane p-0" id="security"
                              role="tabpanel">
                              <div class="p-sm-3 p-0">
                              <form @submit.prevent="change_password()">
                              <div class="mb-4">
                                  <label  class="form-label">{{__('current password')}} </label>
                                  <input type="password" :class="{ 'is-invalid': errors.current_password } " v-model="form2.current_password" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                  <div class="text-danger" v-if="errors.current_password" v-html="errors.current_password" />
                              </div>
                              <div class="mb-4">
                                  <label  class="form-label">{{__('new password')}} </label>
                                  <input type="password" :class="{ 'is-invalid': errors.password } " v-model="form2.password" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                  <div class="text-danger" v-if="errors.password" v-html="errors.password" />
                              </div>
                              <div class="mb-4">
                                  <label  class="form-label">{{__('confirm password')}} </label>
                                  <input type="password" :class="{ 'is-invalid': errors.password_confirmation } " v-model="form2.password_confirmation" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                              </div>
                              <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                                    <button :disabled="form2.processing" type="submit" class="btn btn-primary m-1"> <i class="ri-save-line"></i> {{ __('change password') }}</button>
                                  </div>
                            </form>
                          </div>
                          </div>
                      </div>

              </div>
          </div>
      </div>

      </div>


    </auth-layout>
</template>

<script>
import AuthLayout from './Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm, router  } from '@inertiajs/vue3';
export default {
  components: { AuthLayout, PageHeader },
  props:{
        errors:Object,
    },
  data(){
        return {
            form:useForm({
                id:this.$page.props.auth.admin.id,
                name:this.$page.props.auth.admin.name,
                email:this.$page.props.auth.admin.email,
                email:this.$page.props.auth.admin.email,
                password:'',
                avatar:'',
                avatar_base64:this.$page.props.auth.admin.avatar,
                password_confirmation:'',
            }),
            form2:useForm({
              current_password:'',
              password:'',
              password_confirmation:'',
            })
        }
    },
    methods:{
      update_profile(){
            this.form.post(route('admin.profile_update'))
        },
        change_password(){
          this.form2.post(route('admin.password_update'))
        },
        upload(e){
          let file=e.target.files[0]
          this.form.avatar=file
          const reader=new FileReader()
          reader.onload= ()=>{
            this.form.avatar_base64=reader.result
          }
          reader.readAsDataURL(file)
        },
        check_src(){
        if(this.form.avatar_base64!=null){
            return this.form.avatar_base64
        }else{
        return this.$page.props.auth.default_img
        }
        },
    }

  }
</script>

