<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/roles" :id="item.id" :checked="checked"></delete-modal>
                <div class="modal fade" id="permissionsModal" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="exampleModalLabel1"> {{ __('permissions') }} </h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" v-if="item">
                                <span v-for="permission,index in item.permissions" :key="index" class="badge fs-14 px-2 my-1 mx-1 bg-primary-transparent">{{ permission.name }}</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-bs-dismiss="modal">{{__('close')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
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
                                <button v-if="checked.length>0 && check_permissions(['delete role'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add role'])" :href="route('roles.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="roles.data.length>0">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('role name')}}</th>
                                    <th scope="col">{{__('permissions')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="role,index in roles.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="role.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                            {{role.name}}
                                    </td>

                                    <td>
                                        <button @click="item=role" data-bs-toggle="modal" data-bs-target="#permissionsModal" class="btn btn-icon btn-primary btn-sm"> <i class="ri-eye-line"></i> </button>

                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link  v-if="check_permissions(['edit role'])" :href="route('roles.edit',role.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="role.name!='super admin' && check_permissions(['delete role'])" @click="item=role" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="roles.links" />
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
  mounted(){

  },
  props:{
    roles:Object
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
        this.form.get('/admin/roles_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.roles.data ? this.checked.length == this.roles.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.roles.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
