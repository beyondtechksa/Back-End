<template>
    <div>
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

    <div class="modal fade " data-bs-backdrop="static" id="childrenModal" tabindex="-1"
    aria-labelledby="childrenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="childrenModalLabel1">
                   <span v-if="selected_size"> {{selected_size.name_tr}} </span>
                </h6>
                <button @click="selected_size=null" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" v-if="selected_size">
                    <table class="table">
                        <thead>
                            <tr>
                                <th> Orginal size Name </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    size Name ({{locale.symbol}})
                                </th>
                                <th> size Collection </th>
                                <th> {{ __('action') }} </th>
                            </tr>
                            </thead>
                           
                            <tbody>
                            <tr v-for="size,index in selected_size.sizes" :key="index">
                                <th>
                                     <input v-model="size.name.or" type="text" class="form-control"> 
                                </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    <input v-model="size.name[locale.symbol]" type="text" class="form-control"> 
                                </th>
                                <th>
                                    <v-select v-model="size.size_id" :options="sizes" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('size_id')" /> 
                                </th>
                              
                                <th>
                                    <div class="btn-list">
                                    <button :disabled="form.progress" @click="update_size(size)" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>  
                                    <button data-bs-toggle="modal" data-bs-target="#warningModal" @click="item=size" class="btn btn-danger btn-icon"> <i class="ri-delete-bin-line"></i> </button>  
                                    </div>
                                </th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal" @click="selected_size=null">Close</button>
               
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
                                <th> Orginal Size Name </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    Size Name ({{locale.symbol}})
                                </th>
                                <th> Size Collection </th>
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
                                </th>
                                <th>
                                    <v-select v-model="form.size_id" :options="sizes" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('size_id')" /> 
                                </th>
                                <th></th>
                                <th>
                                    <button :disabled="form.progress" @click="add_size()" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>  
                                </th>
                            </tr>
                        </thead>
                            <tbody>
                            <tr v-for="size,index in sizes" :key="index">
                                <th scope="row"><input v-model="checked" :value="size.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                <th>
                                     <input v-model="size.name.or" type="text" class="form-control"> 
                                </th>
                                <th v-for="locale,index in $page.props.locales" :key="index">
                                    <input v-model="size.name[locale.symbol]" type="text" class="form-control"> 
                                </th>
                                <th>
                                    <v-select v-model="size.size_id" :options="sizes" :reduce="parent => parent.id" label="name_tr" />
                                     <div class="text-danger" v-html="form.errors.get('size_id')" /> 
                                </th>
                                <th>
                                    <div class="custom-toggle-switch d-flex align-items-center ">
                                        <input :id="'toggleswitchPrimary'+index" :name="'toggleswitch001'+index" type="checkbox" :checked="size.status==1"  @change="size.status=!parseInt(size.status)">
                                        <label :for="'toggleswitchPrimary'+index" class="label-primary"></label>
                                    </div>
                                </th>
                                <th>
                                    <div class="btn-list">
                                    <button data-bs-target="#childrenModal" data-bs-toggle="modal" @click="show_children(size)" v-show="size.sizes && size.sizes.length>0" class="btn btn-info btn-icon"> <i class="ri-eye-line"></i> </button>  
                                    <button :disabled="form.progress" @click="update_size(size)" class="btn btn-primary btn-icon"> <i class="ri-save-line"></i> </button>  
                                    <button data-bs-toggle="modal" data-bs-target="#warningModal" @click="item=size" class="btn btn-danger btn-icon"> <i class="ri-delete-bin-line"></i> </button>  
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

    export default{
        components: { AuthLayout, PageHeader, vSelect},
        props:{
            sizes:Array
        },
        data(){
            return {
                form:new Form({
                    id:null,
                    checked:[],
                    name:{},
                }),
                item:{},
                selected_size:null,
                checked:[]
               
            }
        },
        methods:{
            add_size(){
                this.form.post('/admin/api/create_size')
                     .then((resp)=>{
                        if(!resp.data.size_id){
                            this.sizes.unshift(resp.data)
                        }else{
                            let size = this.sizes.find((e)=>e.id==resp.data.size_id)
                            size.sizes.push(resp.data)
                        }
                        this.form.reset()
                     })
            },
            update_size(size){
                this.form.name=size.name
                this.form.id=size.id
                this.form.size_id=size.size_id
                this.form.status=size.status
                this.form.post('/admin/api/edit_size')
                     .then((resp)=>{
                        let c1 = this.sizes.find((e)=>e.id==size.id)
                        let index1 = this.sizes.indexOf(c1)
                        if(resp.data.size_id){
                            if(this.selected_size && this.selected_size.id!=size.size_id){
                                let c0=this.selected_size.sizes.find((e)=>e.id==size.id)
                                let index0 = this.selected_size.sizes.indexOf(c0)
                                this.selected_size.sizes.splice(index0,1)
                                let new_size = this.sizes.find((e)=>e.id==resp.data.size_id)
                                new_size.sizes.push(resp.data)
                            }else{
                                this.sizes.splice(index1,1)
                                let c3 = this.sizes.find((e)=>e.id==size.size_id)
                                c3.sizes.push(resp.data)
                            }
                        }else{
                            //// add to main sizes array
                            
                            if(c1){
                                this.sizes[index1]=resp.data
                            }else{
                                this.sizes.unshift(resp.data)
                            }

                            // remove from selected size sizes
                            let c2 = this.selected_size.sizes.find((e)=>e.id==size.id)
                            let index2 = this.selected_size.sizes.indexOf(c2)
                            this.selected_size.sizes.splice(index2,1)
                        }
                        this.form.reset()
                     })
            },
            destroy(){
                axios.post('/admin/api/delete_size/'+this.item.id)
                     .then((resp)=>{
                        let index = this.sizes.indexOf(this.item)
                        this.sizes.splice(index,1)
                        $('#warningModal').modal('hide')
                        if(this.selected_size){
                            let size = this.selected_size.sizes.find((e)=>e.id==this.item.id)
                            if(size){
                                let index = this.selected_size.sizes.indexOf(size)
                                this.selected_size.sizes.splice(index,1)
                            }
                        }
                     })
            },
            multi_destroy(){
                axios.post('/admin/api/multi_delete_size',{checked:this.checked})
                     .then((resp)=>{
                        this.checked.forEach((id)=>{
                            let item = this.sizes.find((e)=>e.id==id)
                            let index = this.sizes.indexOf(item)
                            this.sizes.splice(index,1)
                        })
                        this.checked=[]
                        $('#multiWarningModal').modal('hide')
                        
                     })
            },
            show_children(size){
                    this.selected_size=size
                }
        },
        computed: {
            checkAll: {
            get: function () {
                return this.sizes ? this.checked.length == this.sizes.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.sizes.forEach(function (user) {
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
</style>