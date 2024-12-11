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
            <label class="form-label"> {{ __('phone') }} </label>
            <input v-model="form.phone" :placeholder="__('phone')" type="text" class="form-control" :class="{'is-invalid':errors.phone}" >
            <div class="text-danger" v-html="errors.phone" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('email') }} </label>
            <input  v-model="form.email" :placeholder="__('email')" type="email" class="form-control" :class="{'is-invalid':errors.email}" >
            <div class="text-danger" v-html="errors.email" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('password') }} </label>
            <input v-model="form.password" :placeholder="__('password')" type="password" class="form-control" :class="{'is-invalid':errors.password}" >
            <div class="text-danger" v-html="errors.password" />
        </div>
        
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('discount percentage') }} (%) </label>
            <input v-model="form.discount_percentage" :placeholder="__('discount percentage')" type="number" class="form-control" :class="{'is-invalid':errors.discount_percentage}" >
            <div class="text-danger" v-html="errors.discount_percentage" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('website') }} </label>
            <input v-model="form.website" :placeholder="__('website')" type="text" class="form-control" :class="{'is-invalid':errors.website}" >
            <div class="text-danger" v-html="errors.website" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('speciality') }} </label>
            <input v-model="form.speciality" :placeholder="__('speciality')" type="text" class="form-control" :class="{'is-invalid':errors.speciality}" >
            <div class="text-danger" v-html="errors.speciality" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('origin') }} </label>
            <input v-model="form.origin" :placeholder="__('origin')" type="text" class="form-control" :class="{'is-invalid':errors.origin}" >
            <div class="text-danger" v-html="errors.origin" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('address') }} </label>
            <input v-model="form.address" :placeholder="__('address')" type="text" class="form-control" :class="{'is-invalid':errors.address}" >
            <div class="text-danger" v-html="errors.address" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('note') }} </label>
            <input v-model="form.note" :placeholder="__('note')" type="text" class="form-control" :class="{'is-invalid':errors.note}" >
            <div class="text-danger" v-html="errors.note" />
        </div>
        <div class="mb-3 col-md-6">
            <label for="input-label" class="form-label d-block"> {{ __('vendor logo') }} </label>
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

            <div class="text-danger" v-html="errors.logo" />
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
