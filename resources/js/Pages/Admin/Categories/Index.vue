<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/categories" :id="item.id" :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" type="text" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button v-if="checked.length>0 && check_permissions(['delete product category'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add product category'])" :href="route('categories.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="categories.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('category name')}}</th>
                                    <th scope="col">{{__('category slug')}}</th>
                                    <th scope="col">{{__('parent')}}</th>
                                    <th scope="col">{{__('products count')}}</th>
                                    <th scope="col">{{__('date')}}</th>
                                    <th scope="col"> Show In Navbar </th>
                                    <th scope="col"> mark as top category </th>
                                    <th scope="col"> mark as mobile top category </th>
                                    <th scope="col"> updated by</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="category,index in categories.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="category.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    <div class="">
                                        <img width="50" v-if="category.image" v-lazy="category.image" class=" img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class=" img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                    <td>
                                        {{ substr(category.name.en+' / '+category.name.ar,50)}}

                                    </td>
                                    <td>
                                        {{ category.slug}}

                                    </td>
                                    <td>
                                       <span v-if="category.category_id!=null"> {{category.parent.translated_name}} </span>
                                       <span v-else> {{__('no parent')}} </span>
                                    </td>
                                    <td>
                                        {{category.products_count}}
                                    </td>
                                    <td>
                                        {{formateDate(category.created_at)}}
                                    </td>
                                    <td>
                                        <div class="form-check form-check-lg form-switch">
                                            <input @change="update_shown_status(category)" :checked="category.show_in_navbar!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-lg form-switch">
                                            <input @change="update_top_status(category)" :checked="category.mark_as_top_category!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-lg form-switch">
                                            <input @change="update_top_status(category,'mobile')" :checked="category.mark_as_mobile_top_category!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td>
                                    <td> {{ category.admin?category.admin.name:null }} </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">

                                            <Link href="" v-if="check_permissions(['view product'])" class="btn btn-sm btn-success"><i class="ri-eye-line"></i> {{ __('all products') }}</Link>
                                            <Link v-if="check_permissions(['edit product category'])" :href="route('categories.edit',category.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!category.is_default && check_permissions(['delete product category'])" @click="item=category" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :search="form.search" :links="categories.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                </div>
            </div>
        </auth-layout>
    </div>
</template>


<script>

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import { Form } from 'vform';
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
    categories:Object
  },
  data(){
    return {
        form:useForm({
            search:this.$page.props.ziggy.query.search??null,
        }),
        vform:new Form({
            id:null,
            type:null,
        }),
        item:Object,
        checked:new Array
    }
  },
  methods:{
    filter(){
        this.form.get('/admin/categories')
    },
    update_shown_status(category){
        this.vform.id=category.id
        this.vform.post('/admin/category/update_shown_status')
             .then((resp)=>{

             })
    },
    update_top_status(category,type){
        this.vform.id=category.id
        this.vform.type=type
        this.vform.post('/admin/category/update_top_status')
             .then((resp)=>{

             })
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.categories.data ? this.checked.length == this.categories.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.categories.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
