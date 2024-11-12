<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/collections" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete collection'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add collection'])" :href="route('collections.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="collections.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}} (en)</th>
                                    <th scope="col">{{__('image')}} (ar)</th>
                                    <th scope="col">{{__('collection name')}}</th>
                                    <th scope="col">{{__('collection slug')}}</th>
                                    <th scope="col">{{__('added by')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="collection,index in collections.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="collection.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    <div class="">
                                        <img width="50" v-if="collection.image.en" v-lazy="collection.image.en" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                   <td>
                                    <div class="">
                                        <img width="50" v-if="collection.image.ar" v-lazy="collection.image.ar" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                    <td>
                                        {{collection.name.en}} /
                                        {{collection.name.ar}}
                                    </td>
                                    <td>
                                        {{collection.slug}}
                                    </td>

                                    <td> {{collection.admin?collection.admin.name:null}} </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a target="_blank" :href="get_link(collection)" v-if="check_permissions(['view product'])" class="btn btn-sm btn-success"><i class="ri-eye-line"></i> {{ __('all products') }}</a>
                                            <Link v-if="check_permissions(['edit collection'])" :href="route('collections.edit',collection.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="check_permissions(['delete collection'])" @click="item=collection" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="collections.links" />
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
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
    collections:Object
  },
  data(){
    return {
        form:useForm({
            search:'',
        }),
        item:Object,
        checked:new Array
    }
  },
  methods:{
    filter(){
        this.form.get('/admin/collections_filter')
    },
    get_link(collection){
        return this.$page.props.ziggy.url+'/collections/'+collection.slug
    }
  },
  computed: {
            checkAll: {
            get: function () {
                return this.collections.data ? this.checked.length == this.collections.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.collections.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
