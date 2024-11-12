<template>

    <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <!-- Success Alert -->

        <!-- warningModal Modal -->
        <delete-modal @clearChecked="checked=new Array" url="/admin/admins" :id="item.id" :checked="checked"></delete-modal>


        <div class="card">
        <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-sm-3">
                <div class="search-box">
                    <input v-model="form.search" type="text" class="form-control" placeholder="Search">
                </div>
            </div>

            <!--end col-->
            <div class="col-sm-auto ms-auto">
                <div class="list-grid-nav hstack gap-1">
                    <button v-if="checked.length>0 && check_permissions(['delete admin'])" class="btn-light-danger btn me-2 text-danger " data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{__('delete selected')}}</button>
                    <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{__('filter')}} </button>
                    <Link v-if="check_permissions(['add admin'])" class="btn btn-success" :href="route('admins.create')"><i class="ri-add-fill me-1 align-bottom"></i> {{__('new')}} </Link>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->
        <div class="table-responsive" v-if="admins.meta.total>0">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                        <th scope="col">{{__('name')}}</th>
                        <th scope="col">{{__('email')}}</th>
                        <th scope="col">{{__('role')}}</th>
                        <th scope="col">{{__('created at')}}</th>
                        <th scope="col">{{__('action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="admin,index in admins.data" :key="index">
                        <th scope="row"><input v-model="checked" :value="admin.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                        <td>
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-sm me-2 online avatar-rounded">
                                    <img v-lazy="admin.avatar" alt="img">
                                </span>{{admin.name}}
                            </div>
                        </td>
                        <td>{{admin.email}}</td>
                        <td><span class="badge bg-primary-transparent" v-if="admin.role">{{admin.role}} </span> </td>
                        <td>{{admin.created_at}}</td>


                        <td>
                            <div class="hstack gap-2 fs-15">
                                <Link v-if="check_permissions(['edit admin'])" :href="route('admins.edit',admin.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                <a v-if="!admin.type==1 && check_permissions(['delete admin'])" @click="item=admin" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
            <div class="pagination  mx-2 px-2">
                <Pagination :links="admins.meta.links" />
            </div>
        </div>
        <div v-else>
            <no-elements></no-elements>
        </div>
    </div>
    </div>
    </div>
<!-- end table responsive -->
    </auth-layout>
</template>

<script>
    import AuthLayout from '../Layouts/AuthLayout.vue'
    import { useForm, Head, Link, router } from '@inertiajs/vue3';
    import Pagination from '@/Components/Pagination.vue';
    import PageHeader from '@/Components/PageHeader.vue';
    import DeleteModal from '@/Components/DeleteModal.vue';
    import NoElements from '@/Components/NoElements.vue';

    export default {
        components: { AuthLayout, Head, Link, Pagination, PageHeader, DeleteModal, NoElements },
        props:{
            admins:Object,
            errors:Object,
        },

        data(){
            return {
                form:useForm({
                search:'',
                ids:Array,
                }),
                item:Object,
                checked:new Array
            }
        },
        mounted(){

        },
        methods:{
            filter(){
                this.form.get('/admin/admins_filter')

            },

        },
        computed: {
            checkAll: {
            get: function () {
                return this.admins.data ? this.checked.length == this.admins.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.admins.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

    }
</script>
