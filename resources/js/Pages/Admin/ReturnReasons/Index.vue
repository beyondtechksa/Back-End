<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/return_reasons" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete return reason'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                <Link v-if="check_permissions(['add return reason'])" :href="route('return_reasons.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="return_reasons.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('return_reason name')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="return_reason,index in return_reasons.data" :key="index">
                                    <td>
                                        {{return_reason.name.en}} /
                                        {{return_reason.name.ar}}
                                    </td>


                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <Link v-if="check_permissions(['edit return reason'])" :href="route('return_reasons.edit',return_reason.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                            <a v-if="!return_reason.is_default && check_permissions(['delete return reason'])" @click="item=return_reason" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="return_reasons.links" />
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
    return_reasons:Object
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
        this.form.get('/admin/return_reasons')
    },

  },
  computed: {
            checkAll: {
            get: function () {
                return this.return_reasons.data ? this.checked.length == this.return_reasons.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.return_reasons.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
