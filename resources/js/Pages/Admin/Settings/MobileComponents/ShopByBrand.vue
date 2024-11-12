<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>


    <div class="mb-3">
        <button type="button" @click="vform.category_id=null" class="btn btn-primary me-2 my-2" :class="{'btn-success':vform.category_id==null}"> Best Seller </button>
        <button type="button" @click="vform.category_id=category.id" class="btn btn-primary me-2 my-2" :class="{'btn-success':vform.category_id==category.id}" v-for="category,index in categories" :key="index"> {{category.translated_name}} </button>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ slug }} </span>
            <span @click="new_item()" class="btn btn-primary"> <i class="ri-add-line"></i> {{ __('new') }} </span>
        </div>
        <div class="card-body">
            <form @submit.prevent="!editMode?save_item():update_item()" v-if="show_modal">
            <div class="row position-relative">
                <div class="col-12 row">
                    <div class="col-md-6"  v-for="locale,index in $page.props.locales" :key="index">
                        <label> Text ({{ locale.symbol }}) </label>
                        <input type="text" class="form-control" v-model="vform.text[locale.symbol]" />
                        <div class="text-danger" v-html="vform.errors.get('text')" />
                    </div>
                </div>
                <div class="col-6" >
                    <label class="form-label"> {{ __('image') }}   <span class="text-success"></span> </label>
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
                        <div class="text-danger" v-html="vform.errors.get('images')" />
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label"> collection </label>
                    <select class="form-control" v-model="vform.collection_id">
                        <option v-for="collection,index in collections" :key="index" :value="collection.id">
                            {{collection.translated_name}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="text-danger" v-html="vform.errors.get('collection_id')" />
            <div class="hstack gap-2">
                <button :disabled="vform.busy" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
            </div>

            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> Image </th>
                            <th> Text </th>
                            <th> Collection </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mobile_banner,index in mobile_banners" :key="index" v-show="vform.category_id==mobile_banner.category_id">
                            <th>
                            <img style="width:100px" v-lazy="mobile_banner.single_image">
                            </th>
                            <td> <span v-if="mobile_banner.text"> {{ mobile_banner.text.en }}  / {{ mobile_banner.text.ar }} </span> </td>
                            <th> <span v-if="mobile_banner.collection">{{mobile_banner.collection.translated_name}} </span></th>
                            <th>
                                <div class="btn-list">
                                <button @click="edit_item(mobile_banner)" class="btn btn-primary">
                                    <i class="ri-edit-line"></i>
                                </button>
                                <button @click="delete_item(mobile_banner)" class="btn btn-danger">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import FileManager from '@/Components/FileManager.vue';
import { Form } from 'vform';
import Swal from 'sweetalert2';
export default{
  components: { LangtextInput, FileManager },
  props:{mobile_banners:Object,slug:String,category_id:String,collections:Array,categories:Array},
        data(){
        return {
            vform:new Form({
                id:null,
                text:{},
                images:{},
                single_image:null,
                category_id:this.category_id,
                collection_id:null,
                slug:this.slug,
            }),
            selected_item:null,
            lang:null,
            editMode:false,
            show_modal:false,

        }
    },
    methods:{
        save_item(){
            this.vform.post(route('mobile_settings.store'))
                .then((resp)=>{
                    this.mobile_banners.unshift(resp.data)
                    this.show_modal=false
                })
        },
        update_item(){
            this.vform.post(route('mobile_settings.update'))
                .then((resp)=>{
                    let index = this.mobile_banners.indexOf(this.selected_item)
                    this.mobile_banners[index]=resp.data
                    this.show_modal=false
                })
        },
        new_item(){
            this.editMode=false
            this.show_modal=!this.show_modal
        },
        edit_item(item){
            this.editMode=true
            this.show_modal=true
            this.selected_item=item
            this.vform.fill(item)
        },
        delete_item(item){
            Swal.fire({
                title: this.__('Delete Alert!'),
                text: this.__('Are you sure for this action'),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#390049",
                cancelButtonColor: "#afafaf",
                confirmButtonText: this.__('Yes, delete!'),
            }).then((result) => {
                if (result.value) {
                    this.vform.id=item.id
                    this.vform.post(route('mobile_settings.delete'))
                        .then((resp)=>{
                            let index = this.mobile_banners.indexOf(item)
                            this.mobile_banners.splice(index,1)
                        })
                }
            });
        },
        check_src(){

            if(this.vform.single_image!=null){
                return this.vform.single_image
            }else{
                return this.$page.props.auth.default_img
            }

        },

        remove_item(index){
            this.vform.value.splice(index,1)
        },
        fileUploaded(){
                this.vform.single_image=this.$refs.gallery.selected_media.path
        },

    }
    }

</script>

<style>
.remove-item{position: absolute;right: 0;top: 0;}
</style>
