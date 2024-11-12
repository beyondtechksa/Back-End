
<template>
<auth-layout>
            <div class="container-fluid">
              <delete-modal @clearChecked="checked=[]" url="/admin/pages" :id="item.id" :checked="checked"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" @keyup="filterData"  type="text" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button v-if="checked.length>0 && check_permissions(['delete page'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                <Link v-if="check_permissions(['add page'])" :href="route('pages.create')" class="btn btn-success btn-wave"> <i class="ri-add-line"></i> {{__('new')}}</Link>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive" v-if="pages.data.length>0">
                        <table class="table text-nowrap table-striped">
                          <thead >
                                <tr>
                                    <th scope="col" style="width: 42px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"  v-model="checkAll"
                                                id="responsivetableCheck">
                                            <label class="form-check-label" for="responsivetableCheck"></label>
                                        </div>
                                    </th>
                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                                    <tbody>
                                        <tr v-for="page,index in pages.data" :key="index">
                                            <th scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"  v-model="checked" :value="page.id"
                                                        id="responsivetableCheck01">
                                                    <label class="form-check-label" for="responsivetableCheck01"></label>
                                                </div>
                                            </th>
                                            <td>
                                                <a  href="#" class="fw-medium">#{{page.id}}</a >
                                            </td>
                                            <td>
                                               {{page.translated_name}}
                                              </td>
                                            <td>
                                               {{page.translated_title}}
                                              </td>

                                            <td>
                                                <div class="d-flex flex-wrap gap-2">

                                                  <Link v-if="check_permissions(['edit page'])" :href="route('pages.edit',page.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                                   <a v-if="check_permissions(['delete page'])" @click="item=page" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>

                                                </div>
                                            </td>

                                        </tr>

                                    </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="pages.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                </div>
            </div>
        </auth-layout>
</template>


<script>

import AuthLayout from "../Layouts/AuthLayout.vue"
import PageHeader from '@/Components/PageHeader.vue'
import Swal from "sweetalert2";
import { useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import DeleteModal from '@/Components/DeleteModal.vue';


export default {
  components: {
    AuthLayout,
    PageHeader,
    Pagination,
    DeleteModal
  },
  props:{
    pages:Object
  },
  data(){
    return {
      create_route:'pages.create',
      edit_route:'pages.edit',
      show_route:'pages.show',
      delete_route:'pages.destroy',
      multi_destroy_route:'pages.multi_destroy',
      checked:new Array,
      search:'',
      form:useForm({
        id:'',
        checked:[]
      }),
      item:{}
    }
  },
  methods:{
    filterData() {
      this.$inertia.replace(this.route('pages.index',{search:this.search}))
    },
    destroy(page){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#34c38f",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
        this.form.delete(route(this.delete_route,page.id))
          Swal.fire("Deleted!", "Your item has been deleted.", "success");
        }
      });
    },
    multi_destroy(){
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#34c38f",
        cancelButtonColor: "#f46a6a",
        confirmButtonText: "Yes, delete it!",
      }).then((result) => {
        if (result.value) {
        this.form.checked=this.checked
        this.form.post(route(this.multi_destroy_route))
          Swal.fire("Deleted!", "Your items has been deleted.", "success");
        }
      });
    },

  },
  computed: {
            checkAll: {
            get: function () {
                return this.pages.data ? this.checked.length == this.pages.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.pages.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }
};
</script>
