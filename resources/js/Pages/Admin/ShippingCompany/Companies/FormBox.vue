<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>

        <div class="row">
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('name') }} </label>
            <input v-model="form.name" :placeholder="__('name')" type="text" class="form-control" :class="{'is-invalid':errors.name}" >
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('link') }} </label>
            <input v-model="form.link" :placeholder="__('link')" type="text" class="form-control" :class="{'is-invalid':errors.link}" >
            <div class="text-danger" v-html="errors.link" />
        </div>


        <div class="mb-3 col-md-6">
            <label for="input-label" class="form-label d-block"> {{ __('image') }} </label>
            <div data-bs-toggle="modal" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                <input @input="upload" id="profile-img-file-input" type="file" class="profile-img-file-input">
            <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                <div class="camera p-0 rounded-circle">
                        <span class="avatar-title rounded-circle text-body">
                            <i class="ri-camera-fill"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-danger" v-html="errors.image" />
        </div>
        </div>



    </div>
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import LangtextArea from '@/Components/Elements/LangtextArea.vue';
import FileManager from '@/Components/FileManager.vue';
    export default {
        components:{LangtextInput, LangtextArea, FileManager},
        props:{
            form:Object,
            errors:Object,
        },
        data(){
            return {

            }
        },
        methods:{
        check_src(){
        if(this.form.image!=null){
            return this.form.image
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.image=this.$refs.file.selected_media.path
        },
        }
    }

</script>
