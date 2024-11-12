<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/languages" :id="item.id" :checked="checked"></delete-modal>
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
                                <button v-if="checked.length>0 && check_permissions(['delete language'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                <Link v-if="check_permissions(['add language'])" :href="route('languages.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>


                    <div v-if="languages.data.length>0">
                        <div class="row">
                            <div v-for="language,index in languages.data" :key="index" class="col-sm-6 col-md-4 col-xxl-3">
                                <div class="card custom-card">
                                    <div class="card-body p-0">
                                        <div class="p-3">
                                            <div class="d-flex flex-wrap mb-1">
                                                <a href="javascript:void(0);" class="pe-2">
                                                    <span class="avatar border text-muted text-primary">
                                                        <img v-lazy="language.logo">
                                                    </span>
                                                </a>
                                                <div class="flex-fill">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-1">
                                                        <span class="fw-semibold">{{language.name}}</span>
                                                    </div>
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between fs-12 mb-3">
                                                        <span class="text-muted">{{language.symbol}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="hstack gap-2 fs-15">
                                                    <Link v-if="check_permissions(['view language'])" :href="route('languages.show',language.id)" class="btn btn-primary-ghost btn-wave btn-sm"> {{ __('words') }} </Link>
                                                    <Link v-if="check_permissions(['edit language'])" :href="route('languages.edit',language.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                                    <a v-if="!language.is_default && check_permissions(['delete language'])" @click="item=language" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="languages.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
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
    // let url = window.location.href
    // let field = 'word'
    // if(url.indexOf('?'+field+'=')!=-1){
    //     window.find()
    // }


  },
  props:{
    languages:Object
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
        this.form.get('/admin/languages_filter')
    },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.languages.data ? this.checked.length == this.languages.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.languages.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
