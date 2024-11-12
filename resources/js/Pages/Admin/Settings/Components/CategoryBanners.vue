<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>

        </div>


        <div class="card-body">
            <form @submit.prevent="save_setting()">
                <div class="mb-3">
                    <button type="button" @click="selected_category=null" class="btn btn-primary me-2 my-2" :class="{'btn-success':selected_category==null}"> All </button>
                    <button type="button" @click="selected_category=category.id" class="btn btn-primary me-2 my-2" :class="{'btn-success':selected_category==category.id}" v-for="category,index in categories" :key="index"> {{category.translated_name}} </button>
                </div>
                <collections-modal :collections="collections"></collections-modal>
                <div class="row position-relative" v-for="val,index in form.value" :key="index" v-show="selected_category==null || val.category_id==selected_category">
                <span class="btn btn-icon btn-danger remove-item" @click="remove_item(index)"> <i class="ri-delete-bin-line"></i> </span>
                <div class="col-12">
                    <div class="mb-3">
                    <label class="form-label"> {{__('Banner Link')}} </label>
                     <input type="url" class="form-control" v-model="val.link">

                    </div>
                </div>
                <div class="col-6" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('banner') }} {{index+1}} ({{ locale.symbol }})

                        <span class="text-success">
                           <span v-if="setting.slug=='top_banner'">
                            <span v-if="index==0"> h:375px </span>
                            <span v-else-if="index==1 || index==2"> h:180px </span>
                            <span v-else> h:flexible </span>
                            </span>
                            <span v-else-if="setting.slug=='banners2'">
                                h:flexible
                            </span>
                            <span v-else-if="setting.slug=='small_banners'">
                                h:100px
                            </span>
                        </span>

                    </label>
                    <div data-bs-toggle="modal" @click="selected_index=index,lang=locale.symbol" data-bs-target="#filemanagerModal" for="profile-img-file-input" style="width: 100%;" class="profile-photo-edit avatar-xs">
                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                            <div class="mb-0"><img style="height: 300px;" v-lazy="check_src(index,locale.symbol)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                            <div class="camera p-0 rounded-circle">
                                    <span class="avatar-title rounded-circle text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label"> Category </label>
                    <select class="form-control" v-model="val.category_id">
                        <option v-for="category,index in categories" :key="index" :value="category.id">
                            {{category.translated_name}}
                        </option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label"> Order </label>
                    <input type="number" class="form-control" v-model="val.list">
                </div>
            </div>
            <div class="text-danger" v-html="form.errors.value" />
            <a @click="new_item()" href="javascript:void(0)" class="btn btn-sm mb-3 btn-outline-primary">
                <i class="ri-add-line"></i> New Item
            </a>
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
                value:this.setting.value
            }),
            selected_index:null,
            lang:null,
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
        new_item(){
            this.form.value.push({image:{}})
        },
        check_src(index,lang){
        if(index!=null){
            if(this.form.value[index].image!=null){
                return this.form.value[index].image[lang]
            }else{
                return this.$page.props.auth.default_img
            }
        }
        },
        remove_item(index){
            this.form.value.splice(index,1)
        },
        fileUploaded(){
            if(this.selected_index!=null){
                this.form.value[this.selected_index].image[this.lang]=this.$refs.gallery.selected_media.path
            }
        },

    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>
