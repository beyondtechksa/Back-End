<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
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
                <div class="row">
                    <div class="mb-3">
                        <label for="input-label" class="form-label"> {{ __('language name') }} </label>
                        <input :placeholder="__('enter language name')" v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': errors.name } " >
                        <div class="text-danger" v-html="errors.name" />
                    </div>
                    <div class="mb-3">
                        <label for="input-label" class="form-label"> {{ __('language symbol') }} </label>
                        <input :placeholder="__('enter language symbol')" v-model="form.symbol" type="text" class="form-control" :class="{ 'is-invalid': errors.symbol } " >
                        <div class="text-danger" v-html="errors.symbol" />
                    </div>
                    <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="input-label" class="form-label d-block"> {{ __('language logo') }} </label>
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

                        <div class="text-danger" v-html="errors.logo" />
                    </div>

                </div>
                    <div class="col-lg-12">
                        <div class="hstack gap-2">
                            <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('create')}}  </button>
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
    </div>
</template>


<script>


import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import FileManager from '@/Components/FileManager.vue';
export default {
  components: { AuthLayout, PageHeader, useForm, FileManager},
  props:{
    errors:Object
  },
  data(){
    return {
        form:useForm({
            name:'',
            symbol:'',
            logo:null,
        })
    }
  },
  methods:{
        create(){
            this.form.post(route('languages.store'))
        },
        check_src(){
        if(this.form.logo!=null){
            return this.form.logo
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.logo=this.$refs.file.selected_media.path
        },
  }

}



</script>
