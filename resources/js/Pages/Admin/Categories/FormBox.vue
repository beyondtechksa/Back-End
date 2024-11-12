<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>
        <div class="mb-3">
            <langtext-input :label="__('category name')" :form="form"></langtext-input>
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3">
            <langtext-input :label="__('category menu name')" :form="form" model="menu_name"></langtext-input>
            <div class="text-danger" v-html="errors.menu_name" />
        </div>
        <div class="mb-3">
            <langtext-area :label="__('category description')" :form="form"></langtext-area>

            <div class="text-danger" v-html="errors.desc" />
        </div>

        <div class="mb-3">
            <label class="form-label"> {{ __('main category') }} </label>
            <v-select v-model="form.category_id" :options="get_categories_with_parents(categories_with_parents)" :reduce="category => category.id" label="parents_name" />
            <div class="text-danger" v-html="errors.category_id" />
            <tree  :form="form"></tree>
        </div>

        <div class="mb-3">
            <label for="input-label" class="form-label d-block"> {{ __('category image') }} </label>
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
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
            </div>
            <div class="text-danger" v-html="errors.status" />
        </div>
    </div>
    <!-- {{ get_categories_with_parents() }} -->
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import LangtextArea from '@/Components/Elements/LangtextArea.vue';
import FileManager from '@/Components/FileManager.vue';
import Tree from './Tree.vue';
import vSelect from 'vue-select'
    export default {
        components:{LangtextInput, LangtextArea, FileManager, Tree, vSelect},
        props:{
            form:Object,
            errors:Object,
            categories:Array,
            parents:Array,
            categories_with_parents:Array,
            category:Object
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


<style>
@import "vue-select/dist/vue-select.css";

    .parent,.parent-start{color: #000;
    padding: 4px 10px;
    display: inline-block;
    background: #efefef;
    margin: 5px 15px;
    position: relative;
    border-radius: 4px;
    }
    .parent::before{
        content: '>';
    position: absolute;
    left: -20px;
    font-size: 17px;
    font-weight: bold;
    }

</style>


