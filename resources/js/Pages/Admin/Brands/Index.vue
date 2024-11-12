<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/brands" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete product brand'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add product brand'])" :href="route('brands.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="brands.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('brand name')}}</th>
                                    <th scope="col">{{__('brand slug')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="brand,index in brands.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="brand.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    <div class="">
                                        <img width="50" v-if="brand.image" v-lazy="brand.image" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class="avatar-xl img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                    <td>
                                        {{brand.name.en}} /
                                        {{brand.name.ar}}
                                    </td>
                                    <td>
                                        {{brand.slug}}
                                    </td>



                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <button @click="copy(brand)" class="btn btn-sm btn-warning"><i class="ri-copy-line"></i> copy link</button>
                                            <a target="_blank" :href="$page.props.ziggy.url+'/brands/'+brand.slug" class="btn btn-icon btn-sm btn-primary"><i class="ri-eye-line"></i></a>
                                            <Link v-if="check_permissions(['edit product brand'])" :href="route('brands.edit',brand.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!brand.is_default && check_permissions(['delete product brand'])" @click="item=brand" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="brands.links" />
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
    brands:Object
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
        this.form.get('/admin/brands_filter')
    },
    copy(brand){
        var textToCopy = this.$page.props.ziggy.url+'/brands/'+brand.slug;

        // Use the Clipboard API to write the text to the clipboard
        navigator.clipboard.writeText(textToCopy).then(function() {
            alert('Copied to clipboard: ' + textToCopy);
        }).catch(function(error) {
            console.error('Failed to copy text: ', error);
        });
    }
  },
  computed: {
            checkAll: {
            get: function () {
                return this.brands.data ? this.checked.length == this.brands.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.brands.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
