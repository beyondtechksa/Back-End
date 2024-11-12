<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>
        <div class="mb-3">
            <langtext-input :label="__('collection name')" :form="form" :ai="false"></langtext-input>
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3 col-lg-4">
            <label for="input-label" class="form-label"> {{ __('slug') }} </label>
            <input :placeholder="__('enter slug')" v-model="form.slug" type="text" class="form-control" :class="{ 'is-invalid': errors.slug } " >
            <div class="text-danger" v-html="errors.slug" />
        </div>

        <div class="row">
        <div class="mb-3 col-6" v-for="locale,index in $page.props.locales" :key="index">
            <label for="input-label" class="form-label d-block"> {{ __('collection image') }} ({{ locale.symbol }})</label>
            <div @click="symbol=locale.symbol" data-bs-toggle="modal" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                <input @input="upload" id="profile-img-file-input" type="file" class="profile-img-file-input">
            <div class="profile-admin position-relative d-inline-block mx-auto  mb-4">
                <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src(locale.symbol)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src(locale.symbol)" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
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


        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
            </div>
            <div class="text-danger" v-html="errors.status" />
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
                symbol:null
            }
        },
        methods:{
        check_src(symbol){
        if(this.form.image[symbol]!=null){
            return this.form.image[symbol]
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.image[this.symbol]=this.$refs.file.selected_media.path
        },
        }
    }

</script>
