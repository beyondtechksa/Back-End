<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/product_groups" :id="item.id" :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" type="text" class="form-control" :placeholder="__('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button v-if="checked.length>0 && check_permissions(['delete product'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add product'])" :href="route('product_groups.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="product_groups.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('product_group name')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product_group,index in product_groups.data" :key="index">
                                    <td>
                                        {{product_group.name.en}} /
                                        {{product_group.name.ar}}
                                    </td>


                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['edit product'])" :href="route('product_groups.edit',product_group.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!product_group.is_default && check_permissions(['delete product'])" @click="item=product_group" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="product_groups.links" />
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
    product_groups:Object
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
        this.form.get('/admin/product_groups')
    },

  },
  computed: {
            checkAll: {
            get: function () {
                return this.product_groups.data ? this.checked.length == this.product_groups.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.product_groups.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
