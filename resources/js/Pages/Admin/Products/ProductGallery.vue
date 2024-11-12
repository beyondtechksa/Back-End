<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="tab-pane-box">
        <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('gallery') }} </span>
        <div class="mb-3">
            <label class="form-label"> {{ __('main image') }} </label>
            <div data-bs-toggle="modal" @click="selected_index=null" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                <input  id="profile-img-file-input" type="file" class="profile-img-file-input">
                    <div class="profile-admin position-relative d-inline-block mx-auto">
                    <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src()" class="rounded-circle avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                    <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                    <div class="camera p-0 rounded-circle">
                            <span class="avatar-title text-body">
                                <i class="ri-camera-fill"></i>
                            </span>
                        </div>
                    </div>
            </div>
            <div class="text-danger" v-html="errors.image"></div>
        </div>
            <div class="mb-3 mt-5" >
                <label class="form-label"> {{ __('product gallery') }} </label>
                <div class="">
                    <div class="gallery-element" v-for="file,index in form.files" :key="index">
                        <div class="gallery-options">
                            <button type="button" @click="remove_file(index)" v-if="form.files.length>1" class="btn btn-danger btn-icon btn-sm rounded-circle d-block">
                                <i class="ri-close-line"></i>
                            </button>

                        </div>
                        <div data-bs-toggle="modal" @click="selected_index=index" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                            <input  id="profile-img-file-input" type="file" class="profile-img-file-input">
                                <div class="profile-admin position-relative d-inline-block mx-auto">
                                <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src(index)" class=" avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                                <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src(index)" class=" avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                                <div class="camera p-0 rounded-circle">
                                        <span class="avatar-title rounded-circle text-body">
                                            <i class="ri-camera-fill"></i>
                                        </span>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="gallery-element">
                         <button type="button" @click="new_file()" class="btn btn-primary btn-icon btn-sm rounded-circle d-block">
                                <i class="ri-add-line"></i>
                         </button>
                    </div>



                </div>
            </div>
    </div>
</div>
</template>


<script>
import FileManager from '@/Components/FileManager.vue'
    export default{
  components: { FileManager },
        props:{
            form:Object,
            errors:Object
        },
        data(){
            return {
                selected_index:null
            }
        },
        methods:{
        check_src(index=null){
        if(index!=null){
            if(this.form.files[index].image!=null){
                return this.form.files[index].image
            }else{
                return this.$page.props.auth.default_img
            }
        }else{
            if(this.form.image!=null && this.form.image!=''){
            return this.form.image
            }else{
                return this.$page.props.auth.default_img
            }
        }

        },
        fileUploaded(){
            if(this.selected_index!=null){
                this.form.files[this.selected_index].image=this.$refs.gallery.selected_media.path
            }else{
                this.form.image=this.$refs.gallery.selected_media.path
            }
        },
        new_file(){
            this.form.files.push({id:null,image:null})
        },
        remove_file(index){
            this.form.files.splice(index,1)
        }
        }
    }
</script>


<style>

    .gallery-element{position: relative;margin:10px;width: 100px;display: inline-block;margin: 5px 10px;}
    .gallery-options{position: absolute;right: 0;top: 0;z-index: 9;}

</style>
