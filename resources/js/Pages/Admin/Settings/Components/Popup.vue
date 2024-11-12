<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>
            
        </div>
        <div class="card-body">
            <form @submit.prevent="save_setting()">
            <div class="row position-relative">
                
                <div class="col-6 mb-3" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('Text1') }} ({{ locale.symbol }}) </label>
                    <input type="text" class="form-control" v-model="form.value.text1[locale.symbol]"> 
                </div>
                <div class="col-6 mb-3" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('Text2') }} ({{ locale.symbol }}) </label>
                    <input type="text" class="form-control" v-model="form.value.text2[locale.symbol]"> 
                </div>
                <div class="col-6 mb-3" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('Text3') }} ({{ locale.symbol }}) </label>
                    <input type="text" class="form-control" v-model="form.value.text3[locale.symbol]"> 
                </div>
                <div class="col-6 mb-3" v-for="locale,index2 in $page.props.locales" :key="index2">
                    <label class="form-label"> {{ __('Button text') }} ({{ locale.symbol }}) </label>
                    <input type="text" class="form-control" v-model="form.value.button[locale.symbol]"> 
                </div>
                <div class="col-12">
                    <div class="mb-3">
                    <label class="form-label"> {{__('button Link')}} </label>
                     <input type="url" class="form-control" v-model="form.link"> 
                     <div class="text-danger" v-html="form.errors.link" />
                     </div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch"
                            id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                        <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
                    </div>
                    <div class="text-danger" v-html="form.errors.status" />
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
                link:this.setting.link,
                status:this.setting.status==1?true:false,
            }),
            selected_index:null,
            lang:null
            
        }
    },
    methods:{
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
        remove_item(index){
            this.form.value.splice(index,1)
        },
        fileUploaded(){
      
            this.form.value[this.lang]=this.$refs.gallery.selected_media.path
            
        },
       
    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>