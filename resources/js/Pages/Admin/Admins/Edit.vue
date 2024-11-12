<template>
  <AuthLayout>
    <div class="container-fluid">
    <page-header></page-header>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>


    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title"> {{ ($page.props.page_name) }} </div>
                    <div class="prism-toggle">
                        <button @click="back()" class="btn btn-sm btn-danger-light">{{__('cancel')}} <i class="ri-arrow-left-line ms-2 align-middle d-inline-block"></i></button>
                    </div>
            </div>
                <div class="card-body">
                    <form @submit.prevent="edit">
                              <div class="row">
                                <div class="col-12">
                                    <div class="mb-3 text-center">
                                        <div data-bs-toggle="modal" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <input @input="upload" id="profile-img-file-input" type="file" class="profile-img-file-input">
                                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                                            <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src()" class="rounded-circle avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                                            <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src()" class="rounded-circle avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                                            <div class="camera p-0 rounded-circle">
                                                    <span class="avatar-title rounded-circle text-body">
                                                        <i class="ri-camera-fill"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-danger" v-html="errors.avatar" />
                                    </div>
                                </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{ __('name') }}  </label>
                                        <input :class="{ 'is-invalid': errors.name } " v-model="form.name" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                        <div class="text-danger" v-if="errors.name" v-html="errors.name" />
                                    </div>
                                    </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{ __('email') }}  </label>
                                        <input type="email" :class="{ 'is-invalid': errors.email } " v-model="form.email" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                        <div class="text-danger" v-if="errors.email" v-html="errors.email" />
                                    </div>
                                    </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{ __('role') }}  </label>
                                        <select v-model="form.role" class="form-control form-select-lg" aria-label="Default select example">
                                            <option value=""> {{ __('select role') }} </option>
                                            <option v-for="role,index in roles" :key="index" :value="role.name"> {{ role.name }} </option>
                                        </select>
                                        <div class="text-danger" v-html="errors.role" />
                                    </div>
                                    </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label">  {{ __('password') }}  </label>
                                        <input autocomplete="new-password" type="password" :class="{ 'is-invalid': errors.password } " v-model="form.password" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                        <div class="text-danger" v-if="errors.password" v-html="errors.password" />
                                    </div>
                                    </div>
                                  <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label  class="form-label"> {{ __('confirm password') }}  </label>
                                        <input type="password" :class="{ 'is-invalid': errors.password_confirmation } " v-model="form.password_confirmation" class="form-control border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-lg" >
                                    </div>
                                    </div>


                                  <div class="col-lg-12">
                                      <div class="hstack gap-2">
                                        <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
                                      </div>
                                  </div>
                              </div>
                          </form>
                </div>
            </div>
        </div>
    </div>
    </div>
  </AuthLayout>
</template>

<script>
import PageHeader from '@/Components/PageHeader.vue';
import AuthLayout from '../Layouts/AuthLayout.vue';
import { Link, useForm, router  } from '@inertiajs/vue3';
import FileManager from '@/Components/FileManager.vue';


export default {
    components: { AuthLayout, PageHeader, Link, FileManager },
    props:{
        errors:Object,
        admin:Object,
        roles:Array,
    },
    data(){
        return {
            form:useForm({
                id:this.admin.id,
                name:this.admin.name,
                email:this.admin.email,
                role:this.admin.role,
                avatar:this.admin.avatar,
                password:'',
                password_confirmation:'',
            })
        }
    },
    methods:{
        edit(){
            router.post(route('admins.update',this.form.id),{
                _method: 'put',
                name:this.form.name,
                email:this.form.email,
                password:this.form.password,
                role:this.form.role,
                password_confirmation:this.form.password_confirmation,
                avatar:this.form.avatar,
            })
        },
        fileUploaded(){
           this.form.avatar=this.$refs.file.selected_media.path
        },
        check_src(){
        if(this.form.avatar!=null){
            return this.form.avatar
        }else{
        return this.$page.props.auth.default_img
        }
        },
    }
}
</script>

