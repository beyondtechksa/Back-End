<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>
            <span @click="new_slider()" class="btn btn-primary"> <i class="ri-add-line"></i> {{ __('new') }} </span>
        </div>
        <div class="card-body">
            <form @submit.prevent="save_setting()">
            <div class="row position-relative" v-for="val,index in form.value" :key="index">
                <span class="btn btn-icon btn-danger remove-item" @click="remove_item(index)"> <i class="ri-delete-bin-line"></i> </span>
                <div class="col-12">
                    <label class="form-label"> {{ __('social') }} {{index+1}}  </label>
                    <div data-bs-toggle="modal" @click="selected_index=index" data-bs-target="#filemanagerModal" for="profile-img-file-input" style="width: 100%;" class="profile-photo-edit avatar-xs">
                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                            <div class="mb-0"><img style="height: 300px;" v-lazy="check_src(index)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                            <div class="camera p-0 rounded-circle">
                                    <span class="avatar-title rounded-circle text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                    <label class="form-label"> {{__('Social Link')}} </label>
                     <input type="url" class="form-control" v-model="val.link">
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
export default{
  components: { LangtextInput, FileManager },
    props:{setting:Object,type:String,collections:Array},
        data(){
        return {
            form:useForm({
                id:this.setting.id,
                value:this.setting.value
            }),
            selected_index:null,

        }
    },
    methods:{
        save_setting(){
            this.form.post(route('settings.update'))
        },
        new_slider(){
            this.form.value.unshift({image:null,link:null})
        },
        check_src(index){
        if(index!=null){
            if(this.form.value[index]!=null){
                return this.form.value[index].image
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
                return this.form.value[this.selected_index].image=this.$refs.gallery.selected_media.path

            }
        },

    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>
