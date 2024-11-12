<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>

        </div>
        <div class="card-body">
            <collections-modal :collections="collections"></collections-modal>
            <!-- <div class="mb-3">
                    <button type="button" @click="selected_category=null" class="btn btn-primary me-2" :class="{'btn-success':selected_category==null}"> All </button>
                    <button type="button" @click="selected_category=category.id" class="btn btn-primary me-2" :class="{'btn-success':selected_category==category.id}" v-for="category,index in categories" :key="index"> {{category.translated_name}} </button>
                </div> -->

            <form @submit.prevent="save_setting()">
            <div class="row position-relative">
                <div class="col-12">
                    <div class="mb-3">
                    <label class="form-label"> {{__('Banner Link')}} </label>
                     <input type="url" class="form-control" v-model="form.link">
                     <div class="text-danger" v-html="form.errors.link" />
                     </div>
                </div>


                <div v-show="type=='web'" class="col-6" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('banner') }} ({{ locale.symbol }})  <span class="text-success"> h:flexible </span> </label>
                    <div data-bs-toggle="modal" @click="lang=locale.symbol" data-bs-target="#filemanagerModal" for="profile-img-file-input" style="width: 100%;" class="profile-photo-edit avatar-xs">
                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                            <div class="mb-0"><img style="height: 300px;" v-lazy="check_src(locale.symbol)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                            <div class="camera p-0 rounded-circle">
                                <span class="avatar-title rounded-circle text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="type=='mobile'" class="col-6" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('banner in mobile') }} ({{ locale.symbol }})  <span class="text-success"> h:flexible </span> </label>
                    <div data-bs-toggle="modal" @click="lang=locale.symbol , mobile=true" data-bs-target="#filemanagerModal" for="profile-img-file-input" style="width: 100%;" class="profile-photo-edit avatar-xs">
                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                            <div class="mb-0"><img style="height: 300px;" v-lazy="check_src2(locale.symbol)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                            <div class="camera p-0 rounded-circle">
                                <span class="avatar-title rounded-circle text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-danger" v-html="form.errors.value" />
            <div class="hstack gap-2">
                <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
            </div>

            </form>
        </div>
    </div>
</template>


<script>
import {useForm  } from '@inertiajs/vue3';
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import FileManager from '@/Components/FileManager.vue';
import CollectionsModal from './CollectionsModal.vue';
export default{
  components: { LangtextInput, FileManager, CollectionsModal },
    props:{setting:Object,type:String,collections:Array},
    mounted(){
        this.get_categories()
    },
        data(){
        return {
            form:useForm({
                id:this.setting.id,
                value:this.setting.value,
                link:this.setting.link,
            }),
            selected_index:null,
            lang:null,
            mobile:false,
            categories:[],
            selected_category:null

        }
    },
    methods:{
        get_categories(){
            axios.get('/admin/get_main_categories')
                 .then((resp)=>{
                    this.categories=resp.data
                 })
        },
        save_setting(){
            this.form.post(route('settings.update'))
        },
        new_slider(){
            this.form.value.unshift({})
        },
        check_src(lang){
            if(this.form.value){
                return this.form.value[lang]
            }else{
                return this.$page.props.auth.default_img
            }
        },
        check_src2(lang){
            if(this.form.value){
                return this.form.value['mobile_'+lang]
            }else{
                return this.$page.props.auth.default_img
            }
        },
        remove_item(index){
            this.form.value.splice(index,1)
        },
        fileUploaded(){
            if(this.mobile){
                this.form.value['mobile_'+this.lang]=this.$refs.gallery.selected_media.path
                this.mobile=false
            }else{

                this.form.value[this.lang]=this.$refs.gallery.selected_media.path
            }

        },

    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>
