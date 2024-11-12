<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>

        </div>
        <div class="card-body">
            <form @submit.prevent="save_setting()">
            <div class="row position-relative">

                <div >
                    <label class="form-label"> Upload Image</label>
                    <div data-bs-toggle="modal" data-bs-target="#filemanagerModal" for="profile-img-file-input" style="width: 100%;" class="profile-photo-edit avatar-xs">
                        <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                            <div class="mb-0"><img style="height: 300px;" v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
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
export default{
  components: { LangtextInput, FileManager },
    props:{setting:Object,type:String,collections:Array},
        data(){
        return {
            form:useForm({
                id:this.setting.id,
                value:this.setting.value,
            }),
            lang:null

        }
    },
    methods:{
        save_setting(){
            this.form.post(route('settings.update'))
        },
        check_src(){
            if(this.form.value){
                return this.form.value
            }else{
                return this.$page.props.auth.default_img
            }

        },

        fileUploaded(){

            this.form.value=this.$refs.gallery.selected_media.path

        },

    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>
