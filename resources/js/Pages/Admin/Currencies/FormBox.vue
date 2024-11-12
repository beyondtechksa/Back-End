<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>
        <div class="row">
        <div class="mb-3 col-lg-4">
            <label for="input-label" class="form-label"> {{ __('currency name') }} </label>
            <input :placeholder="__('enter currency name')" v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': errors.name } " >
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3 col-lg-4">
            <label for="input-label" class="form-label"> {{ __('currency prefix') }} </label>
            <input :placeholder="__('enter currency prefix')" v-model="form.prefix" type="text" class="form-control" :class="{ 'is-invalid': errors.prefix } " >
            <div class="text-danger" v-html="errors.prefix" />
        </div>
        <div class="mb-3 col-lg-4">
            <label for="input-label" class="form-label d-block"> {{ __('currency image') }} </label>
            <div data-bs-toggle="modal" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                <input @input="upload" id="profile-img-file-input" type="file" class="profile-img-file-input">
            <div class="profile-admin position-relative d-inline-block mx-auto ">
                <div  style="width:60px;height: 60px;" v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img style="width:40px;height:40px" v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                <div style="width:60px;height: 60px;" v-else class="css-bar mb-0 css-bar-primary"><img style="width:40px;height:40px" v-lazy="check_src()" class="avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                <div class="camera p-0 rounded-circle">
                        <span class="avatar-title rounded-circle text-body">
                            <i class="ri-camera-fill"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-danger" v-html="errors.flag" />
        </div>
        <div class="mb-3 col-lg-12">
            <label for="input-label" class="form-label"> {{ __('currency note') }} </label>
            <input :placeholder="__('enter currency note')" v-model="form.note" type="text" class="form-control" :class="{ 'is-invalid': errors.note } " >
            <div class="text-danger" v-html="errors.note" />
        </div>
        <div class="mb-3 col-lg-12">
            <label for="input-label" class="form-label"> {{ __('currency exchange rate for each ') }} <strong> 1 Dollar (USD) </strong> </label>
            <input :placeholder="__('enter currency exchange rate')"
            v-model="form.exchange_rate" type="number" :step="0.01" :min="0"
             class="form-control" :class="{ 'is-invalid': errors.exchange_rate } " >
            <div class="text-danger" v-html="errors.exchange_rate" />
        </div>
        <!-- <div class="mb-3 col-lg-6">
            <label for="input-label" class="form-label"> Currency Shipping Fees </label>
            <input :placeholder="__('enter currency shipping fees')" v-model="form.shipping_fees" type="number" :step="0.1" :min="0" class="form-control" :class="{ 'is-invalid': errors.shipping_fees } " >
            <div class="text-danger" v-html="errors.shipping_fees" />
        </div>
        <div class="mb-3 col-lg-6">
            <label for="input-label" class="form-label"> Cart Free Shipping On Amount </label>
            <input :placeholder="__('enter currency free shipping amount')" v-model="form.free_shipping_amount" type="number" :step="0.1" :min="0" class="form-control" :class="{ 'is-invalid': errors.free_shipping_amount } " >
            <div class="text-danger" v-html="errors.free_shipping_amount" />
        </div>
        <div class="mb-3 col-lg-6">
            <label for="input-label" class="form-label"> Country Tax  </label>
            <div class="input-group mb-1">
                <span class="input-group-text" id="basic-addon1">
                    %
                </span>
            <input :placeholder="__('enter country tax')" v-model="form.country_tax" type="number" :min="0" class="form-control" :class="{ 'is-invalid': errors.country_tax } " >
            </div>
            <div class="text-danger" v-html="errors.country_tax" />
        </div>
        <div class="mb-3 col-lg-6">
            <label for="input-label" class="form-label"> {{ __('country tax prefix') }} </label>
            <input :placeholder="__('enter country tax prefix')" v-model="form.country_tax_prefix" type="text" class="form-control" :class="{ 'is-invalid': errors.country_tax_prefix } " >
            <div class="text-danger" v-html="errors.country_tax_prefix" />
        </div>
         -->


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
        if(this.form.flag!=null){
            return this.form.flag
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.flag=this.$refs.file.selected_media.path
        },
        }
    }

</script>
