<template>
    <GuestLayout>
      <Head title="Sign In" />
      <div class="container-fluid">
      <div class="row justify-content-center" style="background:rgb(240 241 247)">
      <div class="col-lg-5">
          <div class="mb-3 mt-5 text-center">
              <Link class="logo" :href="route('admin.dashboard')">
              <img v-lazy="logo" width="200" alt="logo" class="desktop-logo">
          </Link>
          </div>
      <div class="card mt-3" style="border-radius: 15px;">
          <div class="card-body p-5">

          <p class="h3 fw-semibold mb-2 text-center"> {{ __('sign in') }} </p>
          <p class="mb-3 text-muted op-7 fw-normal text-center"> {{ __('welcome back') }} !</p>

          <form @submit.prevent="login()">
          <div class="row gy-3">
              <div class="col-xl-12">
                  <label class="form-label text-default">{{__('user name')}}</label>
                  <input :placeholder="__('enter your email address')" v-model="form.email" :class="{'is-invalid':errors.email}" class="form-control">
                  <div class="text-danger mt-2" v-html="errors.email"></div>
              </div>
              <div class="col-xl-12 mb-2">
              <label class="form-label text-default">{{__('password')}}</label>
                  <div class="input-group">
                      <input :placeholder="__('enter your password')" v-model="form.password" id="signin-password" type="password"  :class="{'is-invalid':errors.password}" class="form-control">
                      <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                  </div>
                  <div class="text-danger mt-2" v-html="errors.password"></div>
                  <div class="mt-2">
                      <div class="form-check">
                      <input class="form-check-input primary" v-model="form.remember" type="checkbox" value="" id="defaultCheck1" checked>
                          <label class="form-check-label text-muted fw-normal" for="defaultCheck1">
                              {{__('remember password')}} ?
                          </label>
                      </div>
                  </div>
              </div>
              <div class="col-xl-12 d-grid mt-2">
                  <button :disabled="form.processing" type="submit" class="btn btn-lg btn-primary">{{__('sign in')}}</button>
              </div>
          </div>
          </form>
          </div>

      </div>
      </div>
      </div>
      </div>




    </GuestLayout>
  </template>

  <script>
  import {Link,useForm,Head} from '@inertiajs/vue3'
  import GuestLayout from '../Layouts/GuestLayout.vue';
  export default {
    components:{
    Link,
    GuestLayout,
    Head
    },
    props:{
        errors:Object
    },
    setup(){
        const form =useForm({
            email:'',
            remember:'',
            password:''
        });
        return {form}
    },
    mounted(){
        this.set_settings()
    },
    data(){
        return {
            logo:null,
            favicon:null,
        }
    },
    methods:{
        login(){
            this.form.post(route('admin.dologin'))
        },
        set_settings(){
            axios.get('/get_setting/logo')
            .then((resp) => {
                let logo = resp.data
                this.logo=logo?logo.value:null
            })
        }
    }

  }
  </script>


