<template>
    <div>
        <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
        <auth-layout>
            <div class="modal flip fade" id="warningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-danger fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4"> {{ __('you are about to delete some records that will not be recovered') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                            <a href="javascript:void(0);" @click="destroy()" class="btn btn-danger">{{__('delete')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <div class="modal flip fade" id="multiWarningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-danger fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4"> {{ __('you are about to delete some records that will not be recovered') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                            <a href="javascript:void(0);" @click="multi_destroy()" class="btn btn-danger">{{__('delete')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" data-bs-backdrop="static" id="childrenModal" tabindex="-1"
    aria-labelledby="childrenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="childrenModalLabel1">
                   <span v-if="selected_color"> {{selected_color.name_tr}} </span>
                </h6>
                <button @click="selected_color=null" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" v-if="selected_color">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> Orginal color Name </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    color Name ({{locale.symbol}})
                                </th>
                                <th> Color Collection </th>
                                <th> Color Code </th>
                                <th> Status  </th>
                                <th> {{ __('action') }} </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="color,index in selected_color.colors" :key="index">

                                <th>
                                     <input v-model="color.name.or" type="text" class="form-control">
                                </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    <input v-model="color.name[locale.symbol]" type="text" class="form-control">
                                </th>
                                <th>
                                    <v-select v-model="color.color_id" :options="colors" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('color_id')" />
                                </th>
                                <th>
                                     <input v-if="!color.color_id" v-model="color.color_code" type="color" class="form-control color-picker">
                                </th>
                                <th>
                                    <div class="custom-toggle-switch d-flex align-items-center ">
                                        <input :id="'toggleswitchPrimary'+index" :name="'toggleswitch001'+index" type="checkbox" :checked="color.status==1"  @change="color.status=!parseInt(color.status)">
                                        <label :for="'toggleswitchPrimary'+index" class="label-primary"></label>
                                    </div>
                                </th>
                                <th>
                                    <div class="btn-list">
                                    <button :disabled="form.busy" @click="update_color(color)" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>
                                    <button data-bs-toggle="modal" data-bs-target="#warningModal" @click="item=color" class="btn btn-danger btn-icon"> <i class="ri-delete-bin-line"></i> </button>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal" @click="selected_color=null">Close</button>

            </div>
        </div>
    </div>
</div>


            <div class="container-fluid">
                <page-header></page-header>
                <div class="card card-body">
                    <button :disabled="checked.length==0" data-bs-toggle="modal" data-bs-target="#multiWarningModal"  class="btn btn-danger"> <i class="ri-delete-bin-line"></i> delete selected </button>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                <th> Orginal color Name </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    color Name ({{locale.symbol}})
                                </th>
                                <th> Color Collection </th>
                                <th> Color Code </th>
                                <th> Color Image </th>
                                <th> Status </th>
                                <th> {{ __('action') }} </th>
                            </tr>
                            </thead>
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                     <input v-model="form.name.or" type="text" class="form-control">
                                </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    <input v-model="form.name[locale.symbol]" type="text" class="form-control">
                                    <div class="text-danger" v-html="form.errors.get('name')" />
                                </th>
                                <th>
                                    <v-select v-model="form.color_id" :options="colors" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('color_id')" />
                                </th>
                                <th>
                                     <input v-if="!form.color_id" v-model="form.color_code" type="color" class="form-control color-picker">
                                     <div class="text-danger" v-html="form.errors.get('color_code')" />
                                </th>
                                <th>
                                    <div class="gallery-element" >
                                        <div data-bs-toggle="modal" @click="selected_index=null" data-bs-target="#filemanagerModal" for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <input  id="profile-img-file-input" type="file" class="profile-img-file-input">
                                                <div class="profile-admin position-relative d-inline-block mx-auto">
                                                <div v-if="form.progress" :class="'css-bar mb-0 css-bar-primary css-bar-'+form.progress.percentage"><img v-lazy="check_src()" class=" avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"></div>
                                                <div v-else class="css-bar mb-0 css-bar-primary"><img v-lazy="check_src()" class=" avatar-xl img-thumbnail admin-profile-image " alt="admin-profile-image"> </div>
                                                <div class="camera p-0 rounded-circle">
                                                        <span class="avatar-title rounded-circle text-body">
                                                            <i class="ri-camera-fill"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </th>
                                <th></th>
                                <th>
                                    <button :disabled="form.progress" @click="add_color()" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>
                                </th>
                            </tr>
                        </thead>
                            <tbody>
                            <tr v-for="color,index in colors" :key="index">
                                <th scope="row"><input v-model="checked" :value="color.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                <th>
                                     <input v-model="color.name.or" type="text" class="form-control">
                                </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    <input v-model="color.name[locale.symbol]" type="text" class="form-control">
                                </th>
                                <th>
                                    <v-select v-model="color.color_id" :options="colors" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('color_id')" />
                                </th>
                                <th>
                                     <input v-if="!color.color_id" v-model="color.color_code" type="color" class="form-control color-picker">
                                </th>
                                <th>
                                    <div class="gallery-element" >
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
                                </th>
                                <th>
                                    <div class="custom-toggle-switch d-flex align-items-center ">
                                        <input :id="'toggleswitchPrimary'+index" :name="'toggleswitch001'+index" type="checkbox" :checked="color.status==1"  @change="color.status=!parseInt(color.status)">
                                        <label :for="'toggleswitchPrimary'+index" class="label-primary"></label>
                                    </div>
                                </th>
                                <th>
                                    <div class="btn-list">
                                    <button data-bs-target="#childrenModal" data-bs-toggle="modal" @click="show_children(color)" v-show="color.colors && color.colors.length>0" class="btn btn-info btn-icon"> <i class="ri-eye-line"></i> </button>
                                    <button :disabled="form.busy" @click="update_color(color)" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>
                                    <button data-bs-toggle="modal" data-bs-target="#warningModal" @click="item=color" class="btn btn-danger btn-icon"> <i class="ri-delete-bin-line"></i> </button>
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
        </div>
    </auth-layout>
    </div>
</template>


<script>
import Form from 'vform';
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import vSelect from 'vue-select'
import FileManager from '@/Components/FileManager.vue';

    export default{
        components: { AuthLayout, PageHeader, vSelect, FileManager},
        props:{
            colors:Array,
        },
        data(){
            return {
                form:new Form({
                    id:null,
                    name:{},
                    color_code:null,
                    color_id:null,
                    image:null,
                }),
                item:{},
                selected_color:null,
                selected_index:null,
                checked:[]


            }
        },
        methods:{
            check_src(index=null){
                    if(index!=null){
                        if(this.colors[index].image!=null){
                            return this.colors[index].image
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
                this.colors[this.selected_index].image=this.$refs.gallery.selected_media.path
            }else{
                this.form.image=this.$refs.gallery.selected_media.path
            }
        },
            add_color(){
                this.form.post('/admin/api/create_color')
                     .then((resp)=>{
                        if(!resp.data.color_id){
                            this.colors.unshift(resp.data)
                        }else{
                            let color = this.colors.find((e)=>e.id==resp.data.color_id)
                            color.colors.push(resp.data)
                        }
                        this.form.reset()
                     })
            },
            update_color(color){
                this.form.name=color.name
                this.form.id=color.id
                this.form.color_code=color.color_code
                this.form.color_id=color.color_id
                this.form.image=color.image
                this.form.status=color.status
                this.form.post('/admin/api/edit_color')
                     .then((resp)=>{
                        let c1 = this.colors.find((e)=>e.id==color.id)
                        let index1 = this.colors.indexOf(c1)
                        if(resp.data.color_id){
                            if(this.selected_color && this.selected_color.id!=color.color_id){
                                let c0=this.selected_color.colors.find((e)=>e.id==color.id)
                                let index0 = this.selected_color.colors.indexOf(c0)
                                this.selected_color.colors.splice(index0,1)
                                let new_color = this.colors.find((e)=>e.id==resp.data.color_id)
                                new_color.colors.push(resp.data)
                            }else{
                                this.colors.splice(index1,1)
                                let c3 = this.colors.find((e)=>e.id==color.color_id)
                                c3.colors.push(resp.data)
                            }
                        }else{
                            //// add to main colors array

                            if(c1){
                                this.colors[index1]=resp.data
                            }else{
                                this.colors.unshift(resp.data)
                            }

                            // remove from selected color colors
                            let c2 = this.selected_color.colors.find((e)=>e.id==color.id)
                            let index2 = this.selected_color.colors.indexOf(c2)
                            this.selected_color.colors.splice(index2,1)
                        }
                        this.form.reset()
                     })
            },
            destroy(){
                axios.post('/admin/api/delete_color/'+this.item.id)
                     .then((resp)=>{
                        let index = this.colors.indexOf(this.item)
                        this.colors.splice(index,1)
                        $('#warningModal').modal('hide')
                        if(this.selected_color){
                            let color = this.selected_color.colors.find((e)=>e.id==this.item.id)
                            if(color){
                                let index = this.selected_color.colors.indexOf(color)
                                this.selected_color.colors.splice(index,1)
                            }
                        }
                     })
            },
            multi_destroy(){
                axios.post('/admin/api/multi_delete_color',{checked:this.checked})
                     .then((resp)=>{
                        this.checked.forEach((id)=>{
                            let item = this.colors.find((e)=>e.id==id)
                            let index = this.colors.indexOf(item)
                            this.colors.splice(index,1)
                        })
                        this.checked=[]
                        $('#multiWarningModal').modal('hide')

                     })
            },
            show_children(color){
                this.selected_color=color
            },

        },
        computed: {
            checkAll: {
            get: function () {
                return this.colors ? this.checked.length == this.colors.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.colors.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

    }

</script>


<style>
@import "vue-select/dist/vue-select.css";
    .color-picker{width: 40px;
    height: 40px;
    padding: 0;
    border: 1px solid #ddd;
    cursor: pointer;}

</style>

