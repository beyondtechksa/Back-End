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
                    <div class="mb-3">
                        <label class="form-label"> {{ __('attribute name') }} </label>
                        <div v-for="locale,index in $page.props.locales" :key="index"
                            class="input-group mb-1">
                            <span class="input-group-text" id="basic-addon1">
                                <img v-lazy="locale.logo" width="20" height="20">
                            </span>
                            <input v-model="form.name[locale.symbol]" type="text" class="form-control" :placeholder="__('enter attribute name')+' '+locale.symbol">
                        </div>
                        <div
                            class="input-group mb-1">
                            <span class="input-group-text" id="basic-addon1">
                                <img v-lazy="" width="20" height="20">
                            </span>
                            <input v-model="form.name.tr" type="text" class="form-control" :placeholder="__('enter attribute name')+' tr'">
                        </div>
                        <div class="text-danger" v-html="errors.name" />

                    </div>

                    <div class="mb-3">
                        <label class="form-label"> {{ __('attribute type') }} </label>
                        <select class="form-control" v-model="form.type">
                            <optgroup :label="__('select')">
                                <option value="select"> {{ __('select menu') }} </option>
                                <option value="radio"> {{ __('radio') }} </option>
                                <option value="checkbox"> {{ __('checkbox') }} </option>
                            </optgroup>
                            <optgroup :label="__('enter')">
                                <option value="text"> {{ __('text') }} </option>
                                <option value="textarea"> {{ __('textarea') }} </option>
                            </optgroup>
                            <optgroup :label="__('file')">
                                <option value="file"> {{ __('file') }} </option>
                            </optgroup>
                            <optgroup :label="__('date')">
                                <option value="date"> {{ __('date') }} </option>
                                <option value="time"> {{ __('time') }} </option>
                                <option value="datetime-locale"> {{ __('datetime') }} </option>
                            </optgroup>
                        </select>
                    </div>



                    <div class="mb-3 mt-5" v-if="form.type=='checkbox' || form.type=='radio' || form.type=='select'">
                        <label class="form-label"> {{ __('selection menu') }} </label>
                        <div class="table-responsive">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th> {{ __('option name') }} </th>
                                    <th> {{ __('option image') }} </th>
                                    <th> {{ __('action') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="option,index in form.options" :key="index">
                                    <td>
                                        <div v-for="locale,index in $page.props.locales" :key="index"
                                                class="input-group mb-1">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <img v-lazy="locale.logo" width="20" height="20">
                                                </span>
                                                <input v-model="option.name[locale.symbol]" type="text" class="form-control" :placeholder="__('enter option name')+' '+locale.symbol">
                                            </div>
                                            <div
                                                class="input-group mb-1">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <img v-lazy="" width="20" height="20">
                                                </span>
                                                <input v-model="option.name.tr" type="text" class="form-control" :placeholder="__('enter option name')+' tr'">
                                            </div>
                                    </td>
                                    <td>
                                        <div data-bs-toggle="modal" @click="selected_index=index" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <input  id="profile-img-file-input" type="file" class="profile-img-file-input">
                                                <div class="profile-admin position-relative d-inline-block mx-auto">
                                                <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src(index)" class="rounded-circle avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                                                <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src(index)" class="rounded-circle avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                                                <div class="camera p-0 rounded-circle">
                                                        <span class="avatar-title rounded-circle text-body">
                                                            <i class="ri-camera-fill"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" @click="remove_option(index)" v-if="form.options.length>1" class="btn btn-danger btn-icon">
                                                <i class="ri-close-line"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                     <td> <button type="button" @click="new_option()"  class="btn btn-primary btn-icon">
                                                <i class="ri-add-line"></i>
                                            </button>
                                     </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>


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
import FileManager from '@/Components/FileManager.vue';
export default {
  components: { AuthLayout, PageHeader, useForm, FileManager},
  props:{
    errors:Object,
    categories:Array
  },
  data(){
    return {
        form:useForm({
            name:{},
            type:'select',
            options:[
                {name:{},image:null}
            ]
        }),

        selected_index:null
    }
  },
  methods:{
        create(){
            this.form.post(route('attributes.store'))
        },
        check_src(index){
        if(this.form.options[index].image!=null){
            return this.form.options[index].image
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.options[this.selected_index].image=this.$refs.file.selected_media.path
        },
        new_option(){
            this.form.options.push({id:null,name:{},image:null})
        },
        remove_option(index){
            this.form.options.splice(index,1)
        }
  }

}



</script>
