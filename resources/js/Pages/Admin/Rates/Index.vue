<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/rates" :id="item.id" :checked="checked"></delete-modal>
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
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="rates.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('product')}}</th>
                                    <th scope="col">{{__('user')}}</th>
                                    <th scope="col">{{__('comment')}}</th>
                                    <th scope="col">{{__('rate')}}</th>
                                    <th scope="col">{{__('status')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rate,index in rates.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="rate.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    {{rate.product.name_en}}
                                   </td>
                                    <td>
                                        {{rate.user.name}}

                                    </td>
                                    <td>
                                        {{rate.comment}}
                                    </td>
                                    <td>
                                        {{rate.rate}}
                                    </td>
                                    <td>
                                        <span v-if="rate.status==0" class="badge bg-warning-transparent"> {{ __('not active') }} </span>
                                        <span v-else  class="badge bg-success-transparent"> {{ __('active') }} </span>
                                    </td>


                                    <td>
                                        <button @click="changeStatus(rate)" type="button"
                                                class="btn btn-icon btn-sm btn-primary"><i
                                            class="ri-edit-line"></i></button>
                                        <a @click="item=rate" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>

                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="rates.links" />
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
import Swal from "sweetalert2";
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
      rates:Object
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
        this.form.get('/admin/rates_filter')
    },
      changeStatus(rate) {
          Swal.fire({
              title: "Are you sure?",
              text: "you will change status",
              icon: "success",
              showCancelButton: true,
              confirmButtonColor: "#34c38f",
              cancelButtonColor: "#f46a6a",
              confirmButtonText: "Yes, change it!",
          }).then((result) => {
              if (result.value) {
                  this.$inertia.put(route('rates.changeStatus', rate.id))
                  Swal.fire("status updated!", "Your item has been updated.", "success");
              }
          });
      },
  },
  computed: {
            checkAll: {
            get: function () {
                return this.rates.data ? this.checked.length == this.rates.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.rates.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
