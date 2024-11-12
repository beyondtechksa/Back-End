

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
            <h3>{{__('My Account')}}</h3>
            <p>{{__('Update your details and manage your security')}}</p>
        </div>
        <div class="text-center pt-3">
            <input @change="upload" class="avatar-input" id="avatar" type="file">
            <label for="avatar" class="avatar-box">
            <img v-lazy="check_src()" alt="User Avatar" class="avatar" />
            </label>
            <div class="text-danger" v-html="form.errors.avatar" />
        </div>
        <div class="General-info">
            <div class="d-flex info-title-inner">
            <h3 class="head-title">{{__('General Informations')}}</h3>
            <h3 class="subtitle">{{__('Security')}}</h3>
            </div>
            <div class="General-form">
            <form @submit.prevent="update()" class="row">
                <div class="col-lg-6 col-md-6">
                <div class="user-forminput">
                    <label for="First Name">{{__('Full Name')}}</label>
                    <input v-model="form.name" type="text" />
                    <div class="text-danger"  v-html="form.errors.name" />
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div class="user-forminput">
                    <label for="First Name">{{__('Email Address')}}</label>
                    <input v-model="form.email" type="email" />
                    <div class="text-danger"  v-html="form.errors.email" />
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div class="user-forminput">
                    <label for="First Name">{{__('Phone')}}</label>
                    <!-- <vue-tel-input v-model="form.phone"
                                   :required="true"
                                   :enable-placeholder="true"
                                   :auto-hide-dial-code="false"
                                   :default-country="'sa'"
                                   :ignored-countries="['il']"
                                   :auto-format="true"
                                   mode="international"></vue-tel-input> -->
                   <div class="phone-container d-flex align-items-center">
                    <span class="phone-code"> +966 </span>
                    <input  v-model="form.phone" type="text" maxlength="9">
                    </div>
                    <div class="text-danger"  v-html="form.errors.phone" />
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div class="user-forminput">
                    <label for="First Name">{{__('Password')}}</label>
                    <input v-model="form.password" type="text" placeholder="********" />
                </div>
                </div>
                <div class="col-lg-6 col-md-6">
                <div class="user-forminput">


                    <div class="selector mt-2">
                    <label for="First Name">{{__('Gender')}}</label>
                    <div
                        class="d-flex align-items-center gap-1 mt-3"
                    >
                        <input  v-model="form.gender"
                        type="radio"
                        id="Male"
                        name="fav_language"
                        value="male"
                        />
                          <label for="Male">{{__('Male')}}</label>

                        <input v-model="form.gender"
                        type="radio"
                        id="Female"
                        name="fav_language"
                        value="female"
                        />
                          <label for="Female">{{__('Female')}}</label>
                    </div>
                    </div>
                    <div class="text-danger"  v-html="form.errors.gender" />
                </div>
                </div>
                <div class="col-lg-6 col-12">
                <div class="submit-button">
                    <button class="submit" type="submit">
                    {{__('Update your Infos')}}
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#depositModal" class="DELETE">
                    {{__('DELETE YOUR ACCOUNT')}}
                    </button>
                </div>
                    <div class="modal fade" id="depositModal" tabindex="-1" aria-modal="true" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <form @submit.prevent="store">

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label class="mb-2 text-dark">{{ __('Enter Password:') }}</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control "
                                                       required="" value="">
                                                <div class="text-danger" v-html="errors.password" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="text-dark" data-bs-dismiss="modal">{{ __('close') }}</button>
                                        <div class="prevent-double-click">
                                            <button type="submit" class="btn btn-dark text-white">{{ __('Delete') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </div>
        </div>
            </page-content>

          </div>
        </div>
      </div>

      <!-- ==== Responsive Admin  ==== -->


    </main>
  </app>

</template>



<script>
import App from '@/HomeLayouts/AuthenticatedLayout.vue';
import SideBar from './Components/SideBar.vue';
import PageContent from './Components/PageContent.vue';
import {Link, useForm} from '@inertiajs/vue3';
import { VueTelInput } from 'vue3-tel-input';
import 'vue3-tel-input/dist/vue3-tel-input.css';

export default{
  components: {Link, VueTelInput, App, SideBar, PageContent },
    props:{
        errors:Object,
    },
  data(){
    return {
      form : useForm({
      name:this.$page.props.auth.user.name,
      email:this.$page.props.auth.user.email,
      phone:this.$page.props.auth.user.phone,
      gender:this.$page.props.auth.user.gender,
      avatar:null,
      avatar_base64:this.$page.props.auth.user.avatar,
      password:null,
    }),
        passwordForm: useForm({
            password:null
        })
    }
  },
  methods:{
    update(){
      this.form.post(route('profile.update'))
    },
      store(){
          this.passwordForm.post(route('delete.account'))
      },
      check_src(){
      if(this.form.avatar_base64){
          return this.form.avatar_base64
      }else if(this.form.avatar){
        return this.form.avatar
      }
      return this.form.avatar_base64??this.$page.props.auth.default_img
      },
      upload(e){
        let file = e.target.files[0]
        this.form.avatar=file
        this.form.avatar_base64=file
        const reader=new FileReader()
            reader.onload= ()=>{
                this.form.avatar_base64=reader.result
            }
            reader.readAsDataURL(file)
      }
  }
}
</script>


<style scoped>
    .modal-content{background: #fff;text-align: unset;}
    .btn-dark:hover{color:#fff !important}
    .modal-close:hover {color:#000}
    .avatar-box{width:120px;margin: 0 auto;height: 120px;border-radius:50%;overflow: hidden;cursor: pointer;position: relative;border: 1px solid #ddd;}
    .avatar-box img{width: 100%;height: 100%;}
    .avatar-input{display: none;}
</style>
